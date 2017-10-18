package com.developerkingdom.algod.entities.discipline;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.ManyToOne;
import javax.persistence.Table;

import com.developerkingdom.algod.entities.company.Institution;

import br.com.caelum.vraptor.boilerplate.SimpleEntity;
import br.com.caelum.vraptor.serialization.SkipSerialization;

@Entity(name = DisciplineCategory.TABLE)
@Table(name = DisciplineCategory.TABLE)
public class DisciplineCategory extends SimpleEntity {
	public static final String TABLE = "algod_discipline_category";
	private static final long serialVersionUID = 1L;
	
	@Column(nullable=false, length=255)
	private String name;
	
	@ManyToOne(fetch=FetchType.LAZY, optional=false)
	@SkipSerialization
	private Institution institution;

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public Institution getInstitution() {
		return institution;
	}

	public void setInstitution(Institution institution) {
		this.institution = institution;
	}
}
