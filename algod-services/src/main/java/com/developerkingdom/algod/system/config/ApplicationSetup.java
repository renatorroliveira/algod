package com.developerkingdom.algod.system.config;

import java.security.GeneralSecurityException;
import java.security.SecureRandom;
import java.security.cert.CertificateException;
import java.security.cert.X509Certificate;

import javax.ejb.Startup;
import javax.enterprise.context.ApplicationScoped;
import javax.enterprise.event.Observes;
import javax.inject.Inject;
import javax.net.ssl.KeyManager;
import javax.net.ssl.SSLContext;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;
import javax.servlet.ServletContext;

import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;
import org.jboss.logging.Logger;

import com.developerkingdom.algod.entities.user.User;
import com.developerkingdom.algod.entities.user.authz.AccessLevels;

import br.com.caelum.vraptor.boilerplate.HibernateDAO;
import br.com.caelum.vraptor.boilerplate.factory.SessionFactoryProducer;
import br.com.caelum.vraptor.boilerplate.factory.SessionManager;
import br.com.caelum.vraptor.boilerplate.util.CryptManager;
import br.com.caelum.vraptor.boilerplate.util.EmailUtils;

@ApplicationScoped
@Startup
public class ApplicationSetup {

	private static final Logger LOG = Logger.getLogger(ApplicationSetup.class);

	protected ApplicationSetup() {
	}

	public void initializeAtStartup(@Observes ServletContext context) {

	}

	@Inject
	public ApplicationSetup(SessionFactoryProducer factoryProducer) {
		factoryProducer.initialize("hibernate.cfg.xml");
		
		CryptManager.updateKey(SystemConfigs.getConfig("crypt.key"));
		CryptManager.updateSalt("-!0Ao8", "4Pq@1-");

		EmailUtils.setDefaultFrom("noreply@nilopecanha.org", "Plataforma Nilo Peçanha");
		EmailUtils.setSmtpSettings(
			SystemConfigs.getConfig("smtp.host"),
			Integer.parseInt(SystemConfigs.getConfig("smtp.port")),
			SystemConfigs.getConfig("smtp.user"),
			SystemConfigs.getConfig("smtp.password"),
			"true".equals(SystemConfigs.getConfig("smtp.ssl"))
		);

		SessionManager mngr = new SessionManager(factoryProducer.getInstance());
		HibernateDAO dao = new HibernateDAO(mngr);
		LOG.info("Overwriting SSL context to ignore invalid certificates...");
		try {
			SSLContext ctx = SSLContext.getInstance("TLS");
			ctx.init(new KeyManager[0], new TrustManager[] { new DefaultTrustManager() }, new SecureRandom());
			SSLContext.setDefault(ctx);
		} catch (GeneralSecurityException ex) {
			System.out.println("N�o consegui sobrescrever o SSLContext.");
			ex.printStackTrace();
		}

		Criteria criteria = dao.newCriteria(User.class)
			.add(Restrictions.eq("email", "admin@admin"));
		User user = (User) criteria.uniqueResult();
		if (user == null) {
			user = new User();
			user.setEmail("admin@admin");
			user.setName("Administrador do Sistema");
			user.setPassword(CryptManager.passwordHash("12345"));
			user.setAccessLevel(AccessLevels.SYSTEM_ADMIN.getLevel());
			dao.persist(user);
		}

		
		LOG.info("Application setup completed.");
		mngr.closeSession();
	}

	public static class DefaultTrustManager implements X509TrustManager {

		@Override
		public void checkClientTrusted(X509Certificate[] arg0, String arg1) throws CertificateException {
		}

		@Override
		public void checkServerTrusted(X509Certificate[] arg0, String arg1) throws CertificateException {
		}

		@Override
		public X509Certificate[] getAcceptedIssuers() {
			return null;
		}
	}
}
