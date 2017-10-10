package com.developerkingdom.algod.entities.discipline;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.Table;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;

@Entity(name = DisciplineCategory.TABLE)
@Table(name = DisciplineCategory.TABLE)
public class DisciplineCategory extends SimpleLogicalDeletableEntity {
	public static final String TABLE = "algod_discipline_category";
	private static final long serialVersionUID = 1L;
	
	@Column(nullable=false, length=255)
	private String name;

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}
}
