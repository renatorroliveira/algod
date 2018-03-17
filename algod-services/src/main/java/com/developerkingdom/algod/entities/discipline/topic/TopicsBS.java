package com.developerkingdom.algod.entities.discipline.topic;

import java.util.LinkedList;
import java.util.List;

import javax.enterprise.context.RequestScoped;

import org.hibernate.Criteria;
import org.hibernate.criterion.Order;
import org.hibernate.criterion.Restrictions;

import com.developerkingdom.algod.entities.discipline.Discipline;

import br.com.caelum.vraptor.boilerplate.HibernateBusiness;

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
}
