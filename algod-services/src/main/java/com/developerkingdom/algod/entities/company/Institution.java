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
	
	@Column(nullable=false, length=128, unique=true)
	private String name;
	
	@Column(nullable=true, length=4000)
	private String description;
	
	@Column(nullable=true, length=128)
	private String site;
	
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
	
}
