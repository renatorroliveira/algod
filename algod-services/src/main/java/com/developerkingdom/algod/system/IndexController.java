package com.developerkingdom.algod.system;

import java.io.IOException;

import javax.inject.Inject;

import com.developerkingdom.algod.system.config.SystemConfigs;
import com.google.gson.Gson;

import br.com.caelum.vraptor.Controller;
import br.com.caelum.vraptor.Get;
import br.com.caelum.vraptor.Path;
import br.com.caelum.vraptor.boilerplate.AbstractController;
import br.com.caelum.vraptor.boilerplate.NoCache;
import br.com.caelum.vraptor.boilerplate.i18n.MessageBundle;

@Controller
public class IndexController extends AbstractController {
	
	@Inject private MessageBundle messages;
	
	@Path(value="/", priority=Path.HIGHEST)
	@NoCache
	public void index() {
		
	}
	
	@Get("/environment")
	public void envInfo() {
		StringBuilder body = new StringBuilder();
		Gson gson = this.gsonBuilder.create();
		
		body.append("EnvInfo={");
		body.append("'baseUrl': '").append(SystemConfigs.getConfig("sys.baseurl")).append("'");
		body.append(",'company': null");
		body.append(",'messages':").append(this.messages.getJSONMessages());
		body.append("};");
		try {
			this.response.setCharacterEncoding("UTF-8");
			this.response.addHeader("Content-Type", "text/javascript"); 
			this.response.getWriter().print(body.toString());
		} catch (IOException ex) {
			LOGGER.error("Unexpected runtime error", ex);
		}
		this.result.nothing();
	}
}
