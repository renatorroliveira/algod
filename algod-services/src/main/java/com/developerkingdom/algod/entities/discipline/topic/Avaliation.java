package com.developerkingdom.algod.entities.discipline.topic;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.ManyToOne;
import javax.persistence.Table;

import com.developerkingdom.algod.entities.user.User;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;


@Entity(name = Avaliation.TABLE)
@Table(name = Avaliation.TABLE)
public class Avaliation extends SimpleLogicalDeletableEntity {
	public final static String TABLE = "algod_discipline_task_sends_avaliation";
	private static final long serialVersionUID = 1L;
	
	@ManyToOne(fetch=FetchType.EAGER)
	private User user;
	
	@ManyToOne(fetch=FetchType.EAGER)
	private Send send;
	
	@Column(nullable=true)
	private String comment;
	
	private double score = 0.0;

	public User getUser() {
		return user;
	}

	public void setUser(User user) {
		this.user = user;
	}
	
	public Send getSend() {
		return send;
	}

	public void setSend(Send send) {
		this.send = send;
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
