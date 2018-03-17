package com.developerkingdom.algod.entities.discipline.topic;

import javax.persistence.CascadeType;
import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.ManyToOne;
import javax.persistence.Table;

import com.developerkingdom.algod.entities.discipline.Discipline;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;

@Entity(name = Topic.TABLE)
@Table(name = Topic.TABLE)
public class Topic extends SimpleLogicalDeletableEntity {
	public static final String TABLE = "algod_discipline_topics";
	private static final long serialVersionUID = 1L;
	
	@ManyToOne(fetch=FetchType.EAGER, cascade=CascadeType.ALL)
	private Discipline discipline;

	@Column(length=255, nullable=false)
	private String title;
	
	public Discipline getDiscipline() {
		return discipline;
	}

	public void setDiscipline(Discipline discipline) {
		this.discipline = discipline;
	}

	public String getTitle() {
		return title;
	}

	public void setTitle(String title) {
		this.title = title;
	}
}
