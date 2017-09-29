package com.developerkingdom.algod.system.config;

import javax.enterprise.context.ApplicationScoped;
import javax.enterprise.inject.Specializes;

import br.com.caelum.vraptor.config.ApplicationConfiguration;

@Specializes
@ApplicationScoped
public class ApplicationConfig extends ApplicationConfiguration {

	public static final String ENV = SystemConfigs.getConfig("sys.env");
	public static final String URL = SystemConfigs.getConfig("sys.baseurl");
	
	public ApplicationConfig() {
		super(null);
	}
	
	@Override
	public String getApplicationPath() {
		return URL.replaceAll("/$", "");
	}

}
