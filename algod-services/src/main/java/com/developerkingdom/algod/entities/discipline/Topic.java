package com.developerkingdom.algod.entities.discipline;

import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.ManyToOne;
import javax.persistence.Table;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;

@Entity(name = Topic.TABLE)
@Table(name = Topic.TABLE)
public class Topic extends SimpleLogicalDeletableEntity {
	public static final String TABLE = "algod_discipline_topics";
	private static final long serialVersionUID = 1L;
	
	@ManyToOne(fetch=FetchType.EAGER)
	Discipline discipline;
	
	@ManyToOne(fetch=FetchType.EAGER)
	TopicItem topicItem;

	public Discipline getDiscipline() {
		return discipline;
	}

	public void setDiscipline(Discipline discipline) {
		this.discipline = discipline;
	}

	public TopicItem getTopicItem() {
		return topicItem;
	}

	public void setTopicItem(TopicItem topicItem) {
		this.topicItem = topicItem;
	}
}
