package com.developerkingdom.algod.entities.discipline.topic;

import java.io.File;
import java.util.List;

import javax.inject.Inject;

import com.developerkingdom.algod.entities.discipline.Discipline;
import com.developerkingdom.algod.system.UserControlAbstractController;
import com.developerkingdom.algod.system.config.ApplicationSetup;

import br.com.caelum.vraptor.Consumes;
import br.com.caelum.vraptor.Controller;
import br.com.caelum.vraptor.Get;
import br.com.caelum.vraptor.Path;
import br.com.caelum.vraptor.Post;
import br.com.caelum.vraptor.boilerplate.NoCache;
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
	public void uploadFile(long id, UploadedFile file) {
		String path = ApplicationSetup.UPLOAD_PATH;
		String Path = "C:\\";
		File savedPhoto = new File(Path, file.getFileName());
	    try {
			file.writeTo(savedPhoto);
			LOGGER.info(file.getFileName());
		} catch(Exception e) {
			LOGGER.errorf(e, "Erro: %s", e.getMessage());
		}
		this.success(true);
	}
}
