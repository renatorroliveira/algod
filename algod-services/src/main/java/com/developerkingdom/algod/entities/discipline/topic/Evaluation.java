package com.developerkingdom.algod.entities.discipline.topic;

import javax.persistence.CascadeType;
import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.ManyToOne;
import javax.persistence.Table;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;


@Entity(name = Evaluation.TABLE)
@Table(name = Evaluation.TABLE)
public class Evaluation extends SimpleLogicalDeletableEntity {
	public final static String TABLE = "algod_discipline_task_sends_evaluation";
	private static final long serialVersionUID = 1L;

	@ManyToOne(fetch=FetchType.EAGER)
	private TopicItem topicItem;
	
	@Column(nullable=true)
	private String comment;
	
	@ManyToOne(fetch=FetchType.EAGER, cascade = {CascadeType.ALL})
	private Send send;
	
	private double score = 0.0;
	
	public Send getSend() {
		return send;
	}

	public void setSend(Send send) {
		this.send = send;
	}

	public TopicItem getTopicItem() {
		return topicItem;
	}

	public void setTopicItem(TopicItem topicItem) {
		this.topicItem = topicItem;
	}

	public String getComment() {
		return comment;
	}

	public void setComment(String comment) {
		this.comment = comment;
	}

	public double getScore() {
		return score;
	}

	public void setScore(double score) {
		this.score = score;
	}
	
	
}
