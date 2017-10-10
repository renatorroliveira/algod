package com.developerkingdom.algod.entities.discipline;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.ManyToOne;
import javax.persistence.Table;

import com.developerkingdom.algod.entities.company.Institution;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;

@Entity(name = Discipline.TABLE)
@Table(name = Discipline.TABLE)
public class Discipline extends SimpleLogicalDeletableEntity {
	public static final String TABLE = "algod_discipline";
	private static final long serialVersionUID = 1L;
	
	@Column(nullable=false, length=255)
	private String name;
	
	@ManyToOne(targetEntity=DisciplineCategory.class, fetch=FetchType.EAGER, optional=false)
	private DisciplineCategory category;
	
	@ManyToOne(targetEntity=Institution.class, fetch=FetchType.EAGER, optional=false)
	private Institution institution;

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public DisciplineCategory getCategory() {
		return category;
	}

	public void setCategory(DisciplineCategory category) {
		this.category = category;
	}

	public Institution getInstitution() {
		return institution;
	}

	public void setInstitution(Institution institution) {
		this.institution = institution;
	}
}
