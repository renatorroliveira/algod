package com.developerkingdom.algod.entities.company;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.ManyToOne;
import javax.persistence.Table;

import br.com.caelum.vraptor.boilerplate.SimpleEntity;

@Entity(name = InstitutionDomain.TABLE)
@Table(name = InstitutionDomain.TABLE)
public class InstitutionDomain extends SimpleEntity {
	public static final String TABLE = "algod_institution_domains";
	private static final long serialVersionUID = 1L;
	
	@Column(nullable=false, unique=true, length=64)
	private String host;
	
	@Column(nullable=false, length=255)
	private String baseUrl;
	
	@ManyToOne(optional=false)
	private Institution institution;

	public String getHost() {
		return host;
	}

	public void setHost(String host) {
		this.host = host;
	}

	public String getBaseUrl() {
		return baseUrl;
	}

	public void setBaseUrl(String baseUrl) {
		this.baseUrl = baseUrl;
	}

	public Institution getInstitution() {
		return institution;
	}

	public void setInstitution(Institution institution) {
		this.institution = institution;
	}

}
