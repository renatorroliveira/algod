package com.developerkingdom.algod.entities.company;

import java.util.List;

import javax.enterprise.context.RequestScoped;

import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

import br.com.caelum.vraptor.boilerplate.HibernateBusiness;

@RequestScoped
public class InstitutionBS extends HibernateBusiness {
	
	public Institution newInstitution(Institution inst) {
		inst.setId(null);
		inst.setDeleted(false);
		this.persist(inst);
		
		return inst;
	}
	
	public List<Institution> listInstitutions(){
		Criteria criteria = this.dao.newCriteria(Institution.class);
		return this.dao.findByCriteria(criteria, Institution.class);
	}
	
	public Institution deleteInstitution(long id) {
		Criteria criteria = this.dao.newCriteria(Institution.class)
				.add(Restrictions.eq("id", id));
		Institution inst = (Institution) criteria.uniqueResult();
		inst.setDeleted(true);
		this.dao.persist(inst);
		return inst;
	}
}
