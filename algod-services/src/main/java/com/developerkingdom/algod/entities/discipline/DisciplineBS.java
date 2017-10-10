package com.developerkingdom.algod.entities.discipline;

import java.util.List;

import javax.enterprise.context.RequestScoped;

import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

import com.developerkingdom.algod.entities.company.Institution;

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
}
