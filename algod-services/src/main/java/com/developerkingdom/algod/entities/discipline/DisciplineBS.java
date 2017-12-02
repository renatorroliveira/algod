package com.developerkingdom.algod.entities.discipline;

import java.util.Date;
import java.util.List;

import javax.enterprise.context.RequestScoped;

import org.hibernate.Criteria;
import org.hibernate.criterion.Disjunction;
import org.hibernate.criterion.Projections;
import org.hibernate.criterion.Restrictions;

import com.developerkingdom.algod.entities.company.Institution;
import com.developerkingdom.algod.entities.user.User;

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
		
		if (discipline != null) {
			DisciplineUser disciplineUser = new DisciplineUser();
			disciplineUser.setDiscipline(discipline);
			disciplineUser.setUser(user);
//			disciplineUser.setRole();
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
	
	public Topic newTopic(Discipline discipline) {
		Topic topic = new Topic();
		topic.setDiscipline(discipline);
		this.dao.persist(topic);
		return topic;
	}
	
	public TopicItem newTopicItem(Topic topic, TopicItem topicItem) {
		
		topicItem.setId(null);
		topicItem.setTopic(topic);
		this.dao.persist(topicItem);
		
		return topicItem;
	}
	
	public List<TopicItem> listTopics(Discipline discipline) {
		Criteria criteria = this.dao.newCriteria(TopicItem.class)
				.add(Restrictions.eq("deleted", false));
		return (List<TopicItem>) this.dao.findByCriteria(criteria, TopicItem.class);
	}
}
