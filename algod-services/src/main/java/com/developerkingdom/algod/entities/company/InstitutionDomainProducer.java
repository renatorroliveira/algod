package com.developerkingdom.algod.entities.company;

import javax.annotation.PostConstruct;
import javax.enterprise.context.RequestScoped;
import javax.enterprise.inject.Produces;
import javax.inject.Inject;
import javax.servlet.http.HttpServletRequest;

import com.developerkingdom.algod.entities.Current;

@RequestScoped
public class InstitutionDomainProducer {

	@Inject private HttpServletRequest request;
	@Inject private InstitutionBS bs;
	private InstitutionDomain domain;
	
	@PostConstruct
	public void initialize() {
		final String host = request.getHeader("Host");
		this.domain = this.bs.retrieveDomain(host);
	}
	
	@Produces
	@Current
	public InstitutionDomain getCurrentDomain() {
		return this.domain;
	}
}
