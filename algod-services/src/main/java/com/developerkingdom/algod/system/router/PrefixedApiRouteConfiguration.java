package com.developerkingdom.algod.system.router;

import javax.enterprise.context.ApplicationScoped;

@ApplicationScoped
public class PrefixedApiRouteConfiguration {

	private static final String PREFIX = "/api";
	
	public String getRoutesPrefix() {
		return PREFIX;
	}


}
