package com.developerkingdom.algod.entities.company;

import java.util.List;

import javax.enterprise.context.RequestScoped;

import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;

import br.com.caelum.vraptor.boilerplate.HibernateBusiness;

@RequestScoped
public class InstitutionBS extends HibernateBusiness {
	
	public Institution register(Institution inst) {
		this.dao.persist(inst);
		
		return inst;
	}
	
	public List<Institution> listInstitutions(){
		Criteria criteria = this.dao.newCriteria(Institution.class)
				.add(Restrictions.eq("deleted", false));
		return this.dao.findByCriteria(criteria, Institution.class);
	}
	
	public Institution delete(Institution institution) {
		institution.setDeleted(true);
		this.dao.persist(institution);
		
		return institution;
	}
}
