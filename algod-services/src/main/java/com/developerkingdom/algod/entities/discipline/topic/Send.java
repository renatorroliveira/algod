package com.developerkingdom.algod.entities.discipline.topic;

import java.util.Date;

import javax.persistence.Entity;
import javax.persistence.FetchType;
import javax.persistence.ManyToOne;
import javax.persistence.Table;
import javax.persistence.Temporal;
import javax.persistence.TemporalType;

import com.developerkingdom.algod.entities.user.User;

import br.com.caelum.vraptor.boilerplate.SimpleLogicalDeletableEntity;

@Entity(name = Send.TABLE)
@Table(name = Send.TABLE)
public class Send extends SimpleLogicalDeletableEntity {
	public static final String TABLE = "algod_discipline_task_sends";
	private static final long serialVersionUID = 1L;
	
	@ManyToOne(fetch=FetchType.EAGER)
	private User user;
	
	private String contentType;
	
	private String name;
	
	private String description;

	@Temporal(TemporalType.TIMESTAMP)
	private Date sendDate = new Date();
	
	@ManyToOne(fetch=FetchType.EAGER)
	private TopicItem topicItem;

	public User getUser() {
		return user;
	}

	public void setUser(User user) {
		this.user = user;
	}

	public String getDescription() {
		return description;
	}

	public void setDescription(String description) {
		this.description = description;
	}

	public Date getSendDate() {
		return sendDate;
	}

	public void setSendDate(Date date) {
		this.sendDate = date;
	}

	public TopicItem getTopicItem() {
		return topicItem;
	}

	public void setTopicItem(TopicItem topicItem) {
		this.topicItem = topicItem;
	}

	public String getContentType() {
		return contentType;
	}

	public void setContentType(String contentType) {
		this.contentType = contentType;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}
	 
}