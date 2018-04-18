package com.developerkingdom.algod.entities.discipline.topic;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.util.List;

import javax.inject.Inject;
import javax.servlet.http.HttpServletResponse;

import org.apache.commons.fileupload.FileUploadException;

import com.developerkingdom.algod.entities.discipline.Discipline;
import com.developerkingdom.algod.entities.user.authz.Permissioned;
import com.developerkingdom.algod.system.UserControlAbstractController;

import br.com.caelum.vraptor.Consumes;
import br.com.caelum.vraptor.Controller;
import br.com.caelum.vraptor.Get;
import br.com.caelum.vraptor.Path;
import br.com.caelum.vraptor.Post;
import br.com.caelum.vraptor.boilerplate.NoCache;
import br.com.caelum.vraptor.observer.download.Download;
import br.com.caelum.vraptor.observer.download.ZipDownload;
import br.com.caelum.vraptor.observer.upload.UploadSizeLimit;
import br.com.caelum.vraptor.observer.upload.UploadedFile;

@Controller
@Path("/api/v1/topic")
public class TopicsController extends UserControlAbstractController {
	
	@Inject private TopicsBS bs;
	public static final String PATH = "./upload/files";

	@Post("/create")
	@Consumes
	@NoCache
	public void create(Long id, String title) {
		try {
			if (id == null) {
				this.result.notFound();
				return;
			}
			Discipline discipline = this.bs.exists(id, Discipline.class);
			if (discipline != null) {
				Topic topic = this.bs.createTopic(discipline, title);
				this.success(topic);
			} else {
				this.result.notFound();
			}
		} catch (Throwable e) {
			LOGGER.error(e);
			this.fail(e.getMessage());
		}
	}
	
	@Post("/del/{id}")
	@Consumes
	@NoCache
	public void delete(Long id) {
		try {
			Topic topic = this.bs.exists(id, Topic.class);
			if (topic != null) {
				this.bs.deleteTopic(id);
				this.success();
			} else {
				this.result.notFound();
			}
		} catch (Throwable e) {
			LOGGER.error(e);
			this.fail(e.getMessage());
		}
	}
	
	@Post("/{id}/item/add")
	@Consumes
	@NoCache
	public void newItem(Long id, TopicItem topicItem) {
		try {
			Topic topic = this.bs.exists(id, Topic.class);
			if (topic != null) {
				topicItem = this.bs.createTopicItem(topic, topicItem);
				this.success(topicItem);
			} else {
				this.result.notFound();
			}
		} catch (Throwable e) {
			LOGGER.error(e);
			this.fail(e.getMessage());
		}
	}
	
	@Post("/{id}/item/del")
	@Consumes
	@NoCache
	public void deleteTopicItem(long id) {
		try {
			// TODO: Delete topic Item
		} catch (Throwable e) {
			this.fail(e.getMessage());
		}
	}
	
	@Get("/{id}")
	@NoCache
	public void listDisciplineTopics(long id) {
		try {
			Discipline discipline = this.bs.exists(id, Discipline.class);
			if (discipline == null)
				this.result.notFound();
			else {
				List<Topic> list = this.bs.listTopics(discipline);
				this.success(list, (long) list.size());
			}
		} catch (Throwable e) {
			LOGGER.error(e);
			this.fail(e.getMessage());
		}
	}
	
	@Get("/{id}/items")
	@NoCache
	public void listDisciplineTopicItems(long id) {
		try {
			Discipline discipline = this.bs.exists(id, Discipline.class);
			if (discipline == null)
				this.result.notFound();
			else {
				List<TopicItem> list = this.bs.listTopicItems(discipline);
				this.success(list, (long) list.size());
			}
		} catch (Throwable e) {
			LOGGER.error(e);
			this.fail(e.getMessage());
		}
	}

	@Get("/item/{id}")
	@NoCache
	@Consumes
	public void getTopicById(long id) {
		try {
			TopicItem topicItem = this.bs.exists(id, TopicItem.class);
			if (topicItem == null)
				this.result.notFound();
			else
				this.success(topicItem);
		} catch (Throwable e) {
			this.fail(e.getMessage());
		}
	}

	@Post("/task/{id}/upload")
	@UploadSizeLimit(sizeLimit=60 * 1024 * 1024, fileSizeLimit=60 * 1024 * 1024)
	public void uploadFile(long id, UploadedFile file) throws FileUploadException {
		TopicItem topicItem = this.bs.exists(id, TopicItem.class);
		if (topicItem == null)
			this.result.notFound();
		else {
			String filename = this.userSession.getUser().getId().toString() + "_" + topicItem.getId().toString();
			File filesPath = new File(PATH);
			if (!filesPath.exists())
				filesPath.mkdirs();
		    try {
				this.success(this.bs.sendTask(topicItem, file, filename, this.userSession.getUser()));
			} catch(Exception e) {
				this.fail(e.getMessage());
				LOGGER.errorf(e, "Erro: %s", e.getMessage());
			} 
		}
	}
	
	@Post("/task/{id}/upload/multiple")
	@UploadSizeLimit(sizeLimit=60 * 1024 * 1024, fileSizeLimit=60 * 1024 * 1024)
	public void uploadMultipleFiles(long id, UploadedFile[] files) throws FileUploadException {
		TopicItem topicItem = this.bs.exists(id, TopicItem.class);
		if (topicItem == null)
			this.result.notFound();
		else {
			for (UploadedFile file : files) {
				LOGGER.info(file.getFileName());
			}
		}
	}
	
	@Get("/task/{id}/download")
	public Download downloadFile(long id, HttpServletResponse response) throws FileNotFoundException {
		TopicItem topicItem = this.bs.exists(id, TopicItem.class);
		Send send = this.bs.getSend(this.userSession.getUser(), topicItem);
		if (topicItem == null)
			this.result.notFound();
		if (send == null)
			this.fail("Você não possui nenhum envio");
		else {
			String filename = this.userSession.getUser().getId().toString() + "_" + topicItem.getId().toString() + "_" + send.getId().toString();
			File file = new File(PATH, filename);
			if (file.exists()) {
				return this.bs.getFileDownload(response, file, this.userSession.getUser(), filename + "_" + send.getName().toString(), send);
			}
		}
		
		return null;
	}
	
	@Get("/task/{id}/sends")
	public void sends(long id) {
		TopicItem topicItem = this.bs.exists(id, TopicItem.class);
		if (topicItem == null)
			this.result.notFound();
		else {
			List<Send> sends = this.bs.listAllSends(topicItem);
			this.success(sends, (long) sends.size());
		}
	}
	
	@Get("/task/{id}/sends/downloads")
	@Permissioned
	public Download getAllSendsDownloads(long id, HttpServletResponse response) throws IOException {
		TopicItem topicItem = this.bs.exists(id, TopicItem.class);
		if (topicItem == null) {
			this.result.notFound();
			return null;
		}
		else {
			Iterable<java.nio.file.Path> downs = this.bs.listAllSendsDownloads(topicItem, response);
			return new ZipDownload("files.zip", downs);
		}
	}
	
	@Get("/task/{id}/send/loggedUser")
	public void getSend(long id) {
		TopicItem topicItem = this.bs.exists(id, TopicItem.class);
		if (topicItem == null)
			this.result.notFound();
		else {
			this.success(this.bs.getSend(topicItem, this.userSession.getUser()));
		}
	}
}
