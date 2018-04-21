package com.developerkingdom.algod.entities.discipline.topic;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.nio.file.Path;
import java.util.LinkedList;
import java.util.List;

import javax.enterprise.context.RequestScoped;
import javax.servlet.http.HttpServletResponse;

import org.hibernate.Criteria;
import org.hibernate.criterion.Order;
import org.hibernate.criterion.Restrictions;

import com.developerkingdom.algod.entities.discipline.Discipline;
import com.developerkingdom.algod.entities.user.User;
import com.google.common.io.Files;

import br.com.caelum.vraptor.boilerplate.HibernateBusiness;
import br.com.caelum.vraptor.observer.download.Download;
import br.com.caelum.vraptor.observer.download.FileDownload;
import br.com.caelum.vraptor.observer.upload.UploadedFile;

@RequestScoped
public class TopicsBS extends HibernateBusiness {
	
	public Topic createTopic(Discipline discipline, String title) {
		Topic topic = new Topic();
		topic.setDiscipline(discipline);
		topic.setTitle(title);
		this.dao.persist(topic);
		return topic;
	}
	
	public Topic deleteTopic(long id) {
		Topic topic = this.dao.exists(id, Topic.class);
		if (topic == null)
			return null;
		else {
			Criteria criteria = this.dao.newCriteria(TopicItem.class)
					.add(Restrictions.eq("topic", topic));
			List<TopicItem> topicItems = (List<TopicItem>) this.dao.findByCriteria(criteria, TopicItem.class);
			for (int i = 0; i < topicItems.size(); i++) {
				TopicItem topicItem = topicItems.get(i);
				topicItem.setDeleted(true);
				this.dao.persist(topicItem);
			}
			topic.setDeleted(true);
			this.dao.persist(topic);
			return topic;
		}
	}
	
	public TopicItem createTopicItem(Topic topic, TopicItem topicItem) {
		topicItem.setTopic(topic);
		this.dao.persist(topicItem);
		
		return topicItem;
	}
	
	public TopicItem deleteTopicItem(TopicItem topicItem) {
		// TODO: Delete topic item
		return null;
	}
	
	public List<Topic> listTopics(Discipline discipline) {
		Criteria criteria = this.dao.newCriteria(Topic.class)
				.add(Restrictions.eq("deleted", false))
				.add(Restrictions.eq("discipline", discipline));
		return (List<Topic>) this.dao.findByCriteria(criteria, Topic.class);
	}
	
	public List<TopicItem> listTopicItems(Discipline discipline) {
		List<Topic> topics = this.listTopics(discipline);
		LinkedList<TopicItem> list = new LinkedList<TopicItem>();
		
		for (int i = 0; i < topics.size(); i++) {
			Criteria criteria = this.dao.newCriteria(TopicItem.class)
					.add(Restrictions.eq("deleted", false))
					.add(Restrictions.eq("visible", true))
					.add(Restrictions.eq("topic", topics.get(i)))
					.addOrder(Order.asc("topic.id"));
			List<TopicItem> topicItems = (List<TopicItem>) this.dao.findByCriteria(criteria, TopicItem.class);
			if (topicItems != null) {
				for (int j = 0; j < topicItems.size(); j++) {
					list.add(topicItems.get(j));
				}
			}
		}
		return list;
	}
	
	public Send sendTask(TopicItem topicItem, UploadedFile file, String filename, User user) {
		Send send = new Send();
		send.setName(file.getFileName());
		send.setTopicItem(topicItem);
		send.setUser(user);
		send.setContentType(file.getContentType());
		this.dao.persist(send);
		try {
			File newFile = new File(TopicsController.PATH, filename + "_" + send.getId().toString());
			file.writeTo(newFile);
		} catch (Exception e) {
			LOGGER.info(e.getMessage());
		}
		return send;
	}
	
	public Send getSend(User user, TopicItem topicItem) {
		Criteria criteria = this.dao.newCriteria(Send.class)
				.add(Restrictions.eq("deleted", false))
				.add(Restrictions.eq("user", user))
				.add(Restrictions.eq("topicItem", topicItem));
		Send send = (Send) criteria.uniqueResult();
		if (send != null)
			return send;
		return null;
	}
	
	public Download getFileDownload(HttpServletResponse response, File file, User user, String filename, Send send) throws FileNotFoundException {
		response.setHeader("content-type", send.getContentType());
		response.setHeader("filename", filename);
        return new FileDownload(file, send.getContentType().toString(), filename, true);
	}
	
	public List<Send> listAllSends(TopicItem topicItem) {
		Criteria criteria = this.dao.newCriteria(Send.class)
				.add(Restrictions.eq("deleted", false))
				.add(Restrictions.eq("topicItem", topicItem));
		return this.dao.findByCriteria(criteria, Send.class);
	}
	
	public Iterable<Path> listAllSendsDownloads(TopicItem topicItem, HttpServletResponse response) throws IOException {
		List<Path> downList = new LinkedList<Path>();
		List<Send> sends = this.listAllSends(topicItem);
		
		File downPath = new File(TopicsController.PATH + "/downsCopy/");
		if (!downPath.exists()) {
			downPath.mkdirs();
		}
		
		for (int i = 0; i < sends.size(); i++) {
			User user = sends.get(i).getUser();
			String filename = user.getId().toString() + "_" + topicItem.getId().toString() + "_" + sends.get(i).getId().toString();
			File file = new File(TopicsController.PATH, filename);
			File newFile = new File(TopicsController.PATH + "/downsCopy/" + filename + "_" + sends.get(i).getName().toString());
			Files.copy(file, newFile);
			Path sendFile = new File(TopicsController.PATH + "/downsCopy/" + filename + "_" + sends.get(i).getName().toString()).toPath();
			downList.add(sendFile);
		}
		response.setHeader("filename", "Sends_" + topicItem.getLabel().toString());
		return downList;
	}
	
	public void deleteZipFiles(TopicItem topicItem) throws IOException {
		List<Send> sends = this.listAllSends(topicItem);
		for (Send send: sends) {
			User user = send.getUser();
			String fullPath = 
					TopicsController.PATH + user.getId().toString() + "_" + topicItem.getId().toString() + "_" + send.getId().toString() + send.getName().toString();
			File file = new File(fullPath);
			if (file.exists()) {
				file.delete();
			}
		}
	}
	
	public Send getSend(TopicItem topicItem, User user) {
		Criteria criteria = this.dao.newCriteria(Send.class)
				.add(Restrictions.eq("deleted", false))
				.add(Restrictions.eq("user", user))
				.add(Restrictions.eq("topicItem", topicItem));
		Send send = (Send) criteria.uniqueResult();
		return send;
	}
	
	public Send unsend(TopicItem topicItem, User user) throws IOException {
		Send send = this.getSend(topicItem, user);
		if (send == null)
			return null;
		else {
			send.setDeleted(true);
			this.deleteFile(topicItem, send, user);
			this.dao.persist(send);
			return send;
		}
	}
	
	public void deleteFile(TopicItem topicItem, Send send, User user) throws IOException {
		File file = new File(TopicsController.PATH, user.getId().toString() + "_" + topicItem.getId().toString() + "_" + send.getId().toString());
		if (file.exists()) {
			file.delete();
		}
	}
}
