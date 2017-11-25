package com.developerkingdom.algod.entities.discipline;

import java.util.List;

import javax.enterprise.context.RequestScoped;

import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

import com.developerkingdom.algod.entities.company.Institution;
import com.developerkingdom.algod.entities.user.User;

import br.com.caelum.vraptor.boilerplate.HibernateBusiness;

@RequestScoped
public class DisciplineBS extends HibernateBusiness {
	
	public Discipline create(Discipline discipline, DisciplineCategory category, Institution institution) {
		discipline.setCategory(category);
		discipline.setInstitution(institution);
		this.dao.persist(discipline);
		
		return discipline;
	}
	
	public List<Discipline> list() {
		Criteria criteria = this.dao.newCriteria(Discipline.class)
				.add(Restrictions.eq("deleted", false));
		return this.dao.findByCriteria(criteria, Discipline.class);
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
	
	public Discipline search(String query) {
		Criteria criteria = this.dao.newCriteria(Discipline.class)
				.add(Restrictions.eq("name", query))
				.add(Restrictions.eq("shortName", query))
				.add(Restrictions.eq("deleted", false))
				.add(Restrictions.eq("closed", false));
		return (Discipline) criteria.uniqueResult();
	}
}
