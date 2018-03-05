package com.developerkingdom.algod.entities.discipline;

import java.util.LinkedList;
import java.util.List;

import javax.enterprise.context.RequestScoped;

import org.hibernate.Criteria;
import org.hibernate.criterion.Disjunction;
import org.hibernate.criterion.Order;
import org.hibernate.criterion.Projections;
import org.hibernate.criterion.Restrictions;

import com.developerkingdom.algod.entities.company.Institution;
import com.developerkingdom.algod.entities.user.User;
import com.developerkingdom.algod.entities.user.authz.AccessLevels;

import br.com.caelum.vraptor.boilerplate.HibernateBusiness;
import br.com.caelum.vraptor.boilerplate.bean.PaginatedList;

@RequestScoped
public class DisciplineBS extends HibernateBusiness {
	
	public Discipline create(Discipline discipline, DisciplineCategory category, Institution institution) {
		discipline.setCategory(category);
		discipline.setInstitution(institution);
		this.dao.persist(discipline);
		
		return discipline;
	}
	
	public List<DisciplineUser> list(User user) {
		Criteria crit = this.dao.newCriteria(DisciplineUser.class)
				.add(Restrictions.eq("deleted", false))
				.add(Restrictions.eq("user", user));
		List<DisciplineUser> list = (List<DisciplineUser>) this.dao.findByCriteria(crit, DisciplineUser.class);
		if (list != null) 
			return list;
		return null;
	}
	
	public List<Discipline> listAll() {
		Criteria crit = this.dao.newCriteria(Discipline.class)
				.add(Restrictions.eq("deleted", false));
		List<Discipline> list = (List<Discipline>) this.dao.findByCriteria(crit, Discipline.class);
		if (list != null) 
			return list;
		return null;
	}
	
	public List<DisciplineCategory> listCategory() {
		Criteria criteria = this.dao.newCriteria(DisciplineCategory.class);
		return this.dao.findByCriteria(criteria, DisciplineCategory.class);
	}
	
	public Discipline delete(Discipline discipline) {
		discipline.setDeleted(true);
		this.dao.persist(discipline);
		
		return discipline;
	}
	
	public DisciplineUser subscribe(User user, Discipline discipline, String accessKey) {
		Criteria criteria = this.dao.newCriteria(Discipline.class)
				.add(Restrictions.eq("accessKey", accessKey))
				.add(Restrictions.eq("id", discipline.getId()));
		discipline = (Discipline) criteria.uniqueResult();
		
		Criteria crit = this.dao.newCriteria(DisciplineUser.class)
				.add(Restrictions.eq("user", user))
				.add(Restrictions.eq("discipline", discipline));
		DisciplineUser verifyUserIsSubs = (DisciplineUser) crit.uniqueResult();
		if (verifyUserIsSubs != null) {
			return null;
		}
		
		else if (discipline != null) {
			DisciplineUser disciplineUser = new DisciplineUser();
			disciplineUser.setDiscipline(discipline);
			disciplineUser.setUser(user);
			disciplineUser.setRole(AccessLevels.AUTHENTICATED.getLevel());
			this.dao.persist(disciplineUser);
			
			return disciplineUser;
		}
		return null;
	}
	
	public DisciplineUser editUser(User user, Discipline discipline, DisciplineUser disciplineUser) {
		if (user.getAccessLevel() >= AccessLevels.MANAGER.getLevel()) {
			
			this.dao.persist(disciplineUser);
			
			return disciplineUser;
		}
		return null;
	}
	
	public DisciplineUser unsubscribe( Discipline discipline, User user) {
		Criteria criteria = this.dao.newCriteria(DisciplineUser.class)
			.add(Restrictions.eq("user", user))
			.add(Restrictions.eq("discipline", discipline));
		DisciplineUser disciplineUser = (DisciplineUser) criteria.uniqueResult();
		
		if (disciplineUser != null) {
			this.dao.delete(disciplineUser);
			
			return disciplineUser;
		}
		return null;
	}
	
	public Discipline getDisciplineByShortName(long id) {
		Criteria criteria = this.dao.newCriteria(Discipline.class)
				.add(Restrictions.eq("id", id))
				.add(Restrictions.eq("deleted", false));
		return (Discipline) criteria.uniqueResult();
	}
	
	public DisciplineUser isSubscribed(Discipline discipline, User user) {
		Criteria criteria = this.dao.newCriteria(DisciplineUser.class)
				.add(Restrictions.eq("user", user))
				.add(Restrictions.eq("discipline", discipline));
		return (DisciplineUser) criteria.uniqueResult();
	}
	
	public PaginatedList<Discipline> search(String terms) {
		PaginatedList<Discipline> results = new PaginatedList<Discipline>();
		Criteria criteria = this.dao.newCriteria(Discipline.class);
		criteria.add(Restrictions.eq("deleted", false));
		
		Criteria count = this.dao.newCriteria(Discipline.class)
				.add(Restrictions.eq("deleted", false))
				.setProjection(Projections.countDistinct("id"));

		if (terms != null && !terms.isEmpty()) {
			Disjunction or = Restrictions.disjunction();
			or.add(Restrictions.like("name", "%" + terms + "%").ignoreCase());
			or.add(Restrictions.like("shortName", "%" + terms + "%").ignoreCase());
			criteria.add(or);
			count.add(or);
		}

		results.setList(this.dao.findByCriteria(criteria, Discipline.class));
		results.setTotal((long) count.uniqueResult());
		return results;
	}
	
	public TopicItem newTopicItem(Topic topic, TopicItem topicItem) {
		topicItem.setId(null);
		topicItem.setTopic(topic);
		this.dao.persist(topicItem);
		
		return topicItem;
	}
	
	public Topic remTopic(long id) {
		Topic topic = this.dao.exists(id, Topic.class);
		if (topic != null) {
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
	
	public List<DisciplineUser> listSubscribedUsers(Discipline discipline) {
		Criteria criteria = this.dao.newCriteria(DisciplineUser.class)
				.add(Restrictions.eq("discipline", discipline))
				.add(Restrictions.eq("deleted", false));
		return (List<DisciplineUser>) this.dao.findByCriteria(criteria, DisciplineUser.class);
	}
}
