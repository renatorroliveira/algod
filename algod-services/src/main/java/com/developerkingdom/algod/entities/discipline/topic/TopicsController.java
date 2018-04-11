package com.developerkingdom.algod.entities.discipline.topic;

import java.io.File;
import java.io.FileNotFoundException;
import java.util.Date;
import java.util.Enumeration;
import java.util.List;

import javax.inject.Inject;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.commons.fileupload.FileUploadException;

import com.developerkingdom.algod.entities.discipline.Discipline;
import com.developerkingdom.algod.system.UserControlAbstractController;
import com.developerkingdom.algod.system.config.ApplicationSetup;

import br.com.caelum.vraptor.Consumes;
import br.com.caelum.vraptor.Controller;
import br.com.caelum.vraptor.Get;
import br.com.caelum.vraptor.Path;
import br.com.caelum.vraptor.Post;
import br.com.caelum.vraptor.boilerplate.NoCache;
import br.com.caelum.vraptor.observer.download.Download;
import br.com.caelum.vraptor.observer.download.FileDownload;
import br.com.caelum.vraptor.observer.upload.UploadSizeLimit;
import br.com.caelum.vraptor.observer.upload.UploadedFile;

@Controller
@Path("/api/v1/topic")
public class TopicsController extends UserControlAbstractController {
	
	@Inject private TopicsBS bs;

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
			String filename = file.getFileName();
			long size = file.getSize();
			String contentType = file.getContentType();
			
			File uploadedFile = new File(ApplicationSetup.UPLOAD_PATH);
			if (!uploadedFile.exists())
				uploadedFile.mkdirs();
			File newFile = new File(ApplicationSetup.UPLOAD_PATH, filename);
		    try {
				file.writeTo(newFile);
				this.bs.sendTask(topicItem, file, ApplicationSetup.UPLOAD_PATH, this.userSession.getUser());
				this.success();
			} catch(Exception e) {
				this.fail(e.getMessage());
				LOGGER.errorf(e, "Erro: %s", e.getMessage());
			} 
		}
	}
	
	@Get("/download")
	public Download downloadFile(long id, HttpServletRequest request, HttpServletResponse response) {
		File file = new File(ApplicationSetup.UPLOAD_PATH + "/430915.jpg");
		Enumeration<String> headerNames = request.getHeaderNames();
		String contentType = request.getContentType();
		LOGGER.info(contentType);
        while (headerNames.hasMoreElements()) {
            String key = (String) headerNames.nextElement();
            String value = request.getHeader(key);
            LOGGER.info(key);
            LOGGER.info(value);
        }
        String filename = file.getName();
        try {
        	if (file.isFile()) {
        		FileDownload fileDownload = new FileDownload(file, "img/jpg", filename, true);
				response.setHeader("filename", file.getName());
        		return fileDownload;
        	}
        	return null;
		} catch (FileNotFoundException e) {
			LOGGER.info(e.getMessage());
			e.printStackTrace();
			return null;
		}
	}
}
