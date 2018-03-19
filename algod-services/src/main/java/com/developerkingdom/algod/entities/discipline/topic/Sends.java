package com.developerkingdom.algod.entities.discipline.topic;

import java.util.Date;

import javax.persistence.CascadeType;
import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.ManyToOne;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

import com.developerkingdom.algod.entities.user.User;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;

@Entity(name = Sends.TABLE)
@Table(name = Sends.TABLE)
public class Sends extends SimpleLogicalDeletableEntity {
	public static final String TABLE = "algod_discipline_task_sends";
	private static final long serialVersionUID = 1L;
	
	@ManyToOne(fetch=FetchType.EAGER, cascade=CascadeType.ALL)
	private User user;
	
	private byte[] file;
	
	@Temporal(TemporalType.TIMESTAMP)
	private Date sendDate = new Date();
	
	@ManyToOne(fetch=FetchType.EAGER, cascade=CascadeType.ALL)
	private TopicItem topicItem;

	public User getUser() {
		return user;
	}

	public void setUser(User user) {
		this.user = user;
	}

	public byte[] getFile() {
		return file;
	}

	public void setFile(byte[] file) {
		this.file = file;
	}

	public Date getDate() {
		return sendDate;
	}

	public void setDate(Date date) {
		this.sendDate = date;
	}

	public TopicItem getTopicItem() {
		return topicItem;
	}

	public void setTopicItem(TopicItem topicItem) {
		this.topicItem = topicItem;
	}
	 
}