package com.developerkingdom.algod.entities.discipline;

import java.util.List;

import javax.enterprise.context.RequestScoped;

import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

import com.developerkingdom.algod.entities.company.Institution;
import com.developerkingdom.algod.entities.user.User;
import com.developerkingdom.algod.entities.discipline.DisciplineUser;

import br.com.caelum.vraptor.boilerplate.HibernateBusiness;

@RequestScoped
public class DisciplineBS extends HibernateBusiness {
	
	public Discipline create(Discipline discipline) {
		if (discipline != null) {
			discipline.setName(discipline.getName());
			discipline.setDeleted(false);
			discipline.setId(null);
			this.dao.persist(discipline);
			return discipline;
		}
		return null;
	}
	
	public List<Discipline> list() {
		Criteria criteria = this.dao.newCriteria(Discipline.class);
		return this.dao.findByCriteria(criteria, Discipline.class);
	}
	
	public List<DisciplineCategory> listCategory() {
		Criteria criteria = this.dao.newCriteria(DisciplineCategory.class);
		return this.dao.findByCriteria(criteria, DisciplineCategory.class);
	}
	
	public Discipline delete(long id) {
		Criteria criteria = this.dao.newCriteria(Discipline.class)
				.add(Restrictions.eq("id", id));
		Discipline discipline = (Discipline) criteria.uniqueResult();
		discipline.setDeleted(true);
		this.dao.persist(discipline);
		return discipline;
	}
	
	public DisciplineCategory searchCategoryById(long id) {
		Criteria criteria = this.dao.newCriteria(DisciplineCategory.class)
				.add(Restrictions.eq("id", id));
		return (DisciplineCategory) criteria.uniqueResult();
	}
	
	public Institution searchInstitutionById(long id) {
		Criteria criteria = this.dao.newCriteria(Institution.class)
				.add(Restrictions.eq("id", id));
		return (Institution) criteria.uniqueResult();
	}
	
	public DisciplineUser subscribe(DisciplineUser disciplineUser, User user, Discipline discipline, String accessKey) {
		Criteria criteria = this.dao.newCriteria(Discipline.class)
				.add(Restrictions.eq("accessKey", accessKey))
				.add(Restrictions.eq("shortName", discipline.getShortName()));
		discipline = (Discipline) criteria.uniqueResult();
		
		if (discipline != null) {
			disciplineUser.setDiscipline(discipline);
			disciplineUser.setUser(user);
			disciplineUser.setRole(user.getAccessLevel());
			this.dao.persist(disciplineUser);
			return disciplineUser;
		}
		return null;
	}
	
	public DisciplineUser unsubscribe(DisciplineUser disciplineUser, User user, Discipline discipline) {
		Criteria criteria = this.dao.newCriteria(DisciplineUser.class)
			.add(Restrictions.eq("user", user))
			.add(Restrictions.eq("discipline", discipline));
		disciplineUser = (DisciplineUser) criteria.uniqueResult();
		
		if (disciplineUser != null) {
			disciplineUser.setDeleted(true);
			this.dao.persist(disciplineUser);
			return disciplineUser;
		}
		return null;
	}
}
