package com.developerkingdom.algod.entities.institution;

import javax.enterprise.context.RequestScoped;

import br.com.caelum.vraptor.boilerplate.HibernateBusiness;

@RequestScoped
public class InstitutionBS extends HibernateBusiness {
	
	public Institution newInstitution(Institution inst) {
		inst.setId(null);
		this.persist(inst);
		
		return inst;
	}
	
}
