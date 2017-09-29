package com.developerkingdom.algod.system.config;

import javax.enterprise.inject.Specializes;

import br.com.caelum.vraptor.boilerplate.i18n.DefaultMessageBundleConfig;

@Specializes
public class MessageBundleConfig extends DefaultMessageBundleConfig {

	private static final String BUNDLE_NAME = "properties.messages";
	
	@Override
	public String getBundleResourceName() {
		return BUNDLE_NAME;
	}
}
