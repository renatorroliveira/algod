package com.developerkingdom.algod.entities.company;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Table;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;

@Entity(name = Institution.TABLE)
@Table(name = Institution.TABLE)
public class Institution extends SimpleLogicalDeletableEntity {
	public static final String TABLE = "algod_institutions";
	private static final long serialVersionUID = 1L;
	
	@Column(nullable=false, length=255)
	String name;
	
	@Column(nullable=false, length=255)
	String description;
	
	@Column(nullable=false, length=255)
	String site;
	
	@Column(nullable=false, unique=true, length=255)
	String host;
	
	@Column(nullable=false, length=255)
	String baseUrl;

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public String getSite() {
		return site;
	}

	public void setSite(String site) {
		this.site = site;
	}

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
	
	
}
