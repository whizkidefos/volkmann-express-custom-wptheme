<?php
/**
 * Volkmann Express — Internal Rule-Based Chatbot
 * ─────────────────────────────────────────────────────────────
 * 100% free. Zero external API calls. Zero ongoing costs.
 * All responses live in PHP arrays below — fully editable from
 * the admin at VE Leads → Chatbot Q&A.
 *
 * How it works:
 *  1. User message is lowercased and tokenised.
 *  2. Each intent has a set of trigger keywords/phrases with weights.
 *  3. The intent with the highest combined score wins.
 *  4. If score is below threshold → fallback / ask-to-clarify.
 *  5. Conversation context (last intent) used to improve follow-ups.
 *  6. Contact CTA is surfaced whenever a CTA-flagged intent fires.
 *  7. Admin can add/edit/delete Q&A entries via the admin page.
 */

defined('ABSPATH') || exit;

/* ═══════════════════════════════════════════════════════════════
   INTENT KNOWLEDGE BASE
   ═══════════════════════════════════════════════════════════════ */

function ve_chat_intents(): array {

    $phone   = get_theme_mod('ve_phone',   '+1 (301) 555-0100');
    $email   = get_theme_mod('ve_email',   'info@volkmannexpress.com');
    $address = get_theme_mod('ve_address', '12653 Hillmeade Station Drive, Bowie, MD 20720');
    $c       = home_url('/contact');
    $s       = home_url('/solutions');

    /*
     * Each intent:
     *   'triggers'   => [ 'phrase' => weight, ... ]  (phrases scored by substring match)
     *   'response'   => string  (supports {phone}, {email}, {address}, {contact}, {solutions})
     *   'cta'        => bool    (show "Contact Our Team" button?)
     *   'follow_ups' => []      (suggested quick-reply chips after this message)
     *   'context'    => string  (slug to record as conversation context)
     *   'priority'   => int     (higher = evaluated first; useful for greetings/off-topic)
     */

    $intents = [

        /* ── GREETINGS ──────────────────────────────────────── */
        'greeting' => [
            'triggers'   => ['hello'=>3,'hi '=>3,'hey '=>3,'good morning'=>4,'good afternoon'=>4,'good evening'=>4,'howdy'=>3,"what's up"=>2,'sup '=>1,'greetings'=>3,"how's it going"=>2,'hiya'=>3],
            'response'   => "Hi there! 👋 Welcome to Volkmann Express — I'm your virtual assistant.\n\nWe're an enterprise technology company based in Bowie, MD, helping US businesses with AI, cloud, cybersecurity, data analytics, and more.\n\nWhat can I help you with today?",
            'cta'        => false,
            'follow_ups' => ['What services do you offer?','How do I get started?','Do you serve my industry?'],
            'context'    => 'greeting',
            'priority'   => 90,
        ],

        /* ── FAREWELLS ──────────────────────────────────────── */
        'farewell' => [
            'triggers'   => ['bye'=>4,'goodbye'=>4,'see you'=>3,'talk later'=>3,'thanks for your help'=>4,'that\'s all'=>3,'no more questions'=>3,'done for now'=>3],
            'response'   => "Great talking with you! If you ever have more questions, we're always here.\n\nDon't hesitate to reach out to our team directly at **{email}** or visit our contact page — we'd love to connect.",
            'cta'        => true,
            'follow_ups' => ['Contact the team','Visit our solutions'],
            'context'    => 'farewell',
            'priority'   => 88,
        ],

        /* ── THANKS ─────────────────────────────────────────── */
        'thanks' => [
            'triggers'   => ['thank you'=>4,'thanks'=>3,'appreciate it'=>3,'helpful'=>2,'great answer'=>3,'perfect'=>2,'awesome'=>2],
            'response'   => "You're very welcome! Happy to help.\n\nIs there anything else you'd like to know about our services or how we work?",
            'cta'        => false,
            'follow_ups' => ['Tell me more about your services','How do I contact you?'],
            'context'    => 'thanks',
            'priority'   => 85,
        ],

        /* ── COMPANY OVERVIEW ───────────────────────────────── */
        'about' => [
            'triggers'   => ['who are you'=>5,'about volkmann'=>5,'what is volkmann'=>5,'tell me about'=>3,'what do you do'=>4,'what does volkmann'=>4,'company overview'=>4,'about your company'=>4,'who is volkmann'=>5,'what kind of company'=>3],
            'response'   => "**Volkmann Express** is an enterprise technology partner headquartered in Bowie, Maryland.\n\nWe help mid-market and enterprise organizations across the US deliver measurable results through eight core practice areas: **AI & Machine Learning, Cloud Solutions, Cybersecurity, Data Analytics, Digital Transformation, Custom Software, IoT & Automation,** and **Managed IT Services**.\n\nWe've completed 200+ engagements across 12 industries — with a 98% client retention rate. Our delivery leadership is US-based on every project.",
            'cta'        => false,
            'follow_ups' => ['What services do you offer?','Which industries do you serve?','How do I get started?'],
            'context'    => 'about',
            'priority'   => 70,
        ],

        /* ── LOCATION / CONTACT INFO ────────────────────────── */
        'location' => [
            'triggers'   => ['where are you'=>4,'located'=>3,'location'=>3,'address'=>4,'where is your office'=>5,'bowie'=>4,'maryland'=>3,'which state'=>3,'dc area'=>3,'washington dc'=>3,'dmv area'=>3],
            'response'   => "We're headquartered at:\n\n**{address}**\n\nWe work with clients across the entire United States — our team travels to client sites and also works remotely. Most engagements are led by US-based project managers and delivery leads.\n\nYou can also reach us at **{phone}** or **{email}**.",
            'cta'        => false,
            'follow_ups' => ['How do I schedule a meeting?','Can you work remotely?'],
            'context'    => 'location',
            'priority'   => 70,
        ],

        /* ── CONTACT / GET IN TOUCH ─────────────────────────── */
        'contact' => [
            'triggers'   => ['contact'=>4,'get in touch'=>5,'reach you'=>4,'phone number'=>4,'email address'=>4,'speak to someone'=>5,'talk to a human'=>5,'call you'=>4,'send a message'=>4,'book a call'=>4,'schedule a call'=>5,'schedule a meeting'=>5,'reach out'=>4,'how to contact'=>5],
            'response'   => "Happy to connect! Here's how to reach us:\n\n📍 **{address}**\n📞 **{phone}**\n✉️ **{email}**\n\nOr fill out our contact form and we'll get back to you within one business day — usually faster.",
            'cta'        => true,
            'follow_ups' => ['What happens after I submit the form?','Can I schedule a free consultation?'],
            'context'    => 'contact',
            'priority'   => 80,
        ],

        /* ── ALL SERVICES ───────────────────────────────────── */
        'services_all' => [
            'triggers'   => ['all services'=>5,'what services'=>5,'list of services'=>5,'what do you offer'=>4,'full list'=>4,'everything you do'=>4,'service offerings'=>4,'capabilities'=>3,'practice areas'=>4,'solutions you offer'=>4],
            'response'   => "We offer eight core service lines:\n\n1. **AI & Machine Learning** — predictive models, NLP, computer vision\n2. **Cloud Solutions** — AWS/Azure/GCP migration, FinOps, multi-cloud\n3. **Cybersecurity** — 24/7 SOC, CMMC, zero trust, pen testing\n4. **Data Analytics** — Snowflake/Databricks/BigQuery, BI dashboards\n5. **Digital Transformation** — process automation, ERP modernization\n6. **Custom Software** — web/mobile apps, APIs, enterprise platforms\n7. **IoT & Automation** — IIoT, predictive maintenance, RPA\n8. **Managed IT Services** — NOC monitoring, helpdesk, vCIO\n\nWhich area interests you most?",
            'cta'        => false,
            'follow_ups' => ['AI & Machine Learning','Cloud Solutions','Cybersecurity','Data Analytics'],
            'context'    => 'services_all',
            'priority'   => 75,
        ],

        /* ── AI / ML ────────────────────────────────────────── */
        'ai_ml' => [
            'triggers'   => ['artificial intelligence'=>5,'machine learning'=>5,'ai '=>4,' ai '=>4,'deep learning'=>5,'neural network'=>5,'nlp '=>4,'natural language'=>5,'computer vision'=>5,'predictive model'=>5,'predictive analytics'=>4,'chatbot'=>3,'recommendation engine'=>4,'ml model'=>5,'data science'=>4,'llm'=>4,'large language model'=>4,'generative ai'=>4,'gen ai'=>4],
            'response'   => "Our **AI & Machine Learning** practice covers the full lifecycle — from data audits and model design to production deployment and ongoing monitoring.\n\nKey capabilities:\n- **Predictive Analytics** — demand forecasting, churn prediction, risk scoring\n- **NLP** — document processing, contract review, intelligent search\n- **Computer Vision** — quality inspection, defect detection, safety monitoring\n- **Responsible AI** — explainability layers, bias auditing, NIST AI RMF alignment\n\nWe've deployed ML systems for healthcare organizations in Houston (34% fewer readmissions), retail chains in Chicago (29% revenue uplift), and manufacturers across the Midwest. What's your use case?",
            'cta'        => false,
            'follow_ups' => ['How long does an AI project take?','What data do I need?','Can I see a case study?'],
            'context'    => 'ai_ml',
            'priority'   => 72,
        ],

        /* ── CLOUD ──────────────────────────────────────────── */
        'cloud' => [
            'triggers'   => ['cloud'=>4,'aws'=>5,'azure'=>5,'gcp'=>5,'google cloud'=>5,'amazon web services'=>5,'cloud migration'=>5,'infrastructure'=>3,'kubernetes'=>4,'devops'=>4,'terraform'=>4,'serverless'=>4,'multi-cloud'=>5,'cloud cost'=>4,'finops'=>5,'cloud security'=>3,'fedramp'=>5,'cloud native'=>4],
            'response'   => "Our **Cloud Solutions** practice handles everything from strategy to managed operations across AWS, Azure, and GCP.\n\nHighlights:\n- **Migration** — zero-downtime lift-and-shift or full re-architecture\n- **Infrastructure as Code** — Terraform/Pulumi environments that are reproducible and auditable\n- **FinOps** — we typically find 30–50% cost savings within 90 days\n- **Compliance** — FedRAMP, HIPAA, SOC 2, CMMC cloud posture built in\n\nA recent Atlanta-based logistics client eliminated $4.2M in annual data center costs with a 14-week multi-cloud migration. Want details on a specific area?",
            'cta'        => false,
            'follow_ups' => ['How much does cloud migration cost?','Can you handle FedRAMP?','How long does migration take?'],
            'context'    => 'cloud',
            'priority'   => 72,
        ],

        /* ── CYBERSECURITY ──────────────────────────────────── */
        'cyber' => [
            'triggers'   => ['cybersecurity'=>5,'cyber security'=>5,'security'=>3,'cmmc'=>6,'nist'=>4,'zero trust'=>5,'soc monitoring'=>5,'pen test'=>5,'penetration test'=>5,'threat detection'=>5,'incident response'=>5,'ransomware'=>4,'data breach'=>4,'compliance'=>3,'hipaa security'=>4,'fedramp security'=>4,'iso 27001'=>4,'vulnerability'=>4,'firewall'=>3,'siem'=>5],
            'response'   => "Our **Cybersecurity** practice provides 24/7 SOC-backed protection with US-based analysts.\n\nCore areas:\n- **CMMC 1/2/3** — full prep, assessment support, evidence automation for DoD contractors\n- **Zero Trust Architecture** — NIST SP 800-207 aligned, identity-centric controls\n- **Threat Detection & Response** — AI-powered SIEM, sub-minute detection, automated playbooks\n- **Penetration Testing** — red team, network, application, and social engineering\n- **Incident Response** — 24/7 retainer with 1-hour response SLA\n\nWe recently helped a Northern Virginia defense contractor achieve **CMMC Level 2 in 11 weeks**, securing a $40M DoD contract. What's your compliance situation?",
            'cta'        => false,
            'follow_ups' => ['How does CMMC certification work?','Do you offer a security assessment?','What does 24/7 SOC cost?'],
            'context'    => 'cyber',
            'priority'   => 72,
        ],

        /* ── DATA ANALYTICS ─────────────────────────────────── */
        'data' => [
            'triggers'   => ['data analytics'=>5,'business intelligence'=>5,'bi dashboard'=>5,'data warehouse'=>5,'snowflake'=>5,'databricks'=>5,'bigquery'=>5,'power bi'=>4,'tableau'=>4,'looker'=>4,'data pipeline'=>4,'data engineering'=>5,'data lake'=>5,'etl'=>5,'data governance'=>4,'analytics platform'=>4,'reporting'=>3,'dashboard'=>3,'data strategy'=>4],
            'response'   => "Our **Data Analytics** practice turns raw, siloed data into decisions your executives can act on — fast.\n\nWhat we build:\n- **Modern Data Lakehouses** on Snowflake, Databricks, or BigQuery\n- **BI Dashboards** on Power BI, Looker, or Tableau — built to actually be used\n- **Predictive Models** — demand forecasting, churn, pricing optimization\n- **Data Governance** — CCPA/HIPAA-compliant cataloging, lineage, access control\n\nA Chicago specialty retailer we worked with consolidated 18 data silos and saw a **29% revenue increase in year one**. What does your current data landscape look like?",
            'cta'        => false,
            'follow_ups' => ['What data platforms do you support?','How long to build a dashboard?','Can you help with data governance?'],
            'context'    => 'data',
            'priority'   => 72,
        ],

        /* ── DIGITAL TRANSFORMATION ─────────────────────────── */
        'digital_transform' => [
            'triggers'   => ['digital transformation'=>6,'process automation'=>5,'rpa'=>5,'robotic process'=>5,'erp'=>4,'legacy modernization'=>5,'change management'=>4,'digital workplace'=>4,'microsoft 365'=>4,'sharepoint'=>3,'workflow automation'=>5,'automation strategy'=>4,'modernize'=>3,'digital strategy'=>4,'legacy system'=>4],
            'response'   => "**Digital Transformation** is our broadest practice — we help organizations reinvent operations, technology, and culture together.\n\nKey services:\n- **Process Automation** — RPA (UiPath/Automation Anywhere), intelligent document processing\n- **ERP & Legacy Modernization** — phased SAP/Oracle upgrades without big-bang risk\n- **Digital Workplace** — Microsoft 365, Teams, SharePoint deployed and actually adopted\n- **Change Management** — Prosci-certified programs that ensure technology gets used\n\nA San Antonio manufacturer we partnered with achieved a **43% OEE improvement and $4.8M in annual savings** through IoT and a 6-month transformation program. Where are you in your transformation journey?",
            'cta'        => false,
            'follow_ups' => ['How do you handle change management?','What does RPA implementation involve?','How long does transformation take?'],
            'context'    => 'digital_transform',
            'priority'   => 72,
        ],

        /* ── CUSTOM SOFTWARE ────────────────────────────────── */
        'software' => [
            'triggers'   => ['custom software'=>5,'web application'=>4,'mobile app'=>4,'web app'=>4,'react'=>3,'next.js'=>4,'node.js'=>3,'python'=>3,'api development'=>5,'rest api'=>4,'graphql'=>4,'enterprise software'=>4,'software development'=>4,'app development'=>4,'flutter'=>4,'react native'=>4,'ios app'=>4,'android app'=>4,'saas'=>3,'software platform'=>4],
            'response'   => "Our **Custom Software** team builds bespoke web, mobile, and enterprise applications engineered to last.\n\nCapabilities:\n- **Web Apps** — React/Next.js frontends + Python/Node/.NET backends\n- **Mobile Apps** — React Native or Flutter for iOS and Android\n- **Enterprise Platforms** — ERP extensions, workflow engines, line-of-business tools\n- **APIs** — RESTful and GraphQL with auth, rate limiting, and developer portals\n- **Security-First** — OWASP Top 10, SAST/DAST in CI/CD, pen testing pre-launch\n- **Accessibility** — WCAG 2.1 AA compliance built in from day one\n\nWhat kind of application are you looking to build?",
            'cta'        => false,
            'follow_ups' => ['How long does development take?','Do you offer fixed-price projects?','Can you maintain the app after launch?'],
            'context'    => 'software',
            'priority'   => 72,
        ],

        /* ── IOT & AUTOMATION ───────────────────────────────── */
        'iot' => [
            'triggers'   => ['iot'=>5,'internet of things'=>5,'industrial iot'=>5,'iiot'=>5,'predictive maintenance'=>5,'digital twin'=>5,'smart factory'=>5,'sensors'=>3,'scada'=>4,'plc'=>4,'oee'=>4,'condition monitoring'=>5,'robotic'=>3,'automation'=>3,'quality inspection'=>4,'smart building'=>4,'edge computing'=>4],
            'response'   => "Our **IoT & Automation** practice connects your physical assets to intelligent software — reducing waste, downtime, and manual work.\n\nKey capabilities:\n- **Industrial IoT Platform** — edge devices, secure connectivity, real-time OT/IT integration\n- **Predictive Maintenance** — ML models that predict equipment failure before it happens\n- **Quality Inspection AI** — 100% production inspection at line speed via computer vision\n- **Digital Twin** — physics-based simulation for manufacturing and facilities\n- **Smart Building** — BMS integration, energy optimization, occupancy-driven HVAC/lighting\n\nAn Ohio auto parts manufacturer we deployed IoT across cut scrap rates by **38% and eliminated 2,400 unplanned downtime hours** annually. What equipment or process are you looking to connect?",
            'cta'        => false,
            'follow_ups' => ['What does an IoT pilot look like?','Do you work with existing SCADA systems?','How long to see ROI from IoT?'],
            'context'    => 'iot',
            'priority'   => 72,
        ],

        /* ── MANAGED IT ─────────────────────────────────────── */
        'managed_it' => [
            'triggers'   => ['managed it'=>5,'managed services'=>5,'msp'=>5,'it support'=>4,'helpdesk'=>4,'help desk'=>4,'it outsourcing'=>5,'noc'=>4,'monitoring'=>3,'patch management'=>4,'backup'=>3,'disaster recovery'=>4,'vcio'=>5,'fractional cio'=>5,'it management'=>4,'outsource it'=>5,'it department'=>3],
            'response'   => "Our **Managed IT Services** practice handles proactive monitoring, support, and infrastructure management so your internal team can focus on the business.\n\nWhat's included:\n- **24/7 NOC** — always-on network monitoring with automated alerting\n- **IT Helpdesk** — Tier 1–3 support with SLA-backed response times\n- **Endpoint Management** — patching, antivirus, device lifecycle for all devices\n- **Backup & Disaster Recovery** — 3-2-1 strategy with tested recovery runbooks\n- **vCIO Advisory** — technology roadmapping and board-level reporting\n\nA 600-person New York consulting firm reduced unplanned downtime by **89% and redirected $1.1M in IT spend** to growth initiatives under our managed program. Want to explore what this looks like for your org?",
            'cta'        => false,
            'follow_ups' => ['What does Managed IT cost?','Do you offer a free IT assessment?','What SLAs do you provide?'],
            'context'    => 'managed_it',
            'priority'   => 72,
        ],

        /* ── INDUSTRIES ─────────────────────────────────────── */
        'industries' => [
            'triggers'   => ['industries'=>4,'which industries'=>5,'what industries'=>5,'sectors'=>4,'healthcare'=>3,'manufacturing'=>3,'financial services'=>3,'retail'=>3,'government'=>3,'defense'=>3,'logistics'=>3,'energy'=>3,'agriculture'=>3,'construction'=>3,'telecom'=>3,'media'=>3,'do you work with'=>3,'do you serve'=>4],
            'response'   => "We serve clients across **12 industries** in the US:\n\n🏥 Healthcare & Life Sciences\n🏭 Manufacturing\n💰 Financial Services\n🛍 Retail & E-Commerce\n✈️ Transportation & Logistics\n⚡ Energy & Utilities\n🌾 Agriculture\n🏗 Construction\n🏛 Government & Defense\n📡 Telecommunications\n📺 Media & Entertainment\n💼 Enterprise & Technology\n\nEach vertical has pre-built compliance frameworks, data models, and accelerators tailored to its regulatory environment. Which industry are you in?",
            'cta'        => false,
            'follow_ups' => ['Healthcare','Financial Services','Manufacturing','Government & Defense'],
            'context'    => 'industries',
            'priority'   => 70,
        ],

        /* ── HEALTHCARE ─────────────────────────────────────── */
        'healthcare' => [
            'triggers'   => ['healthcare'=>5,'health system'=>5,'hospital'=>4,'hipaa'=>6,'ehr'=>5,'epic'=>4,'fhir'=>5,'clinical'=>4,'patient'=>4,'readmission'=>4,'electronic health'=>5,'medical'=>3,'health it'=>5,'cms'=>4,'value-based care'=>5,'health network'=>4],
            'response'   => "Healthcare is one of our strongest verticals — we combine deep HIPAA/HITECH compliance expertise with genuine clinical workflow knowledge.\n\nWhat we do for healthcare clients:\n- **HIPAA-Compliant Data Platforms** — unified patient data from EHR, lab, imaging, and community systems\n- **AI Readmission Prediction** — ML models trained on clinical records to identify high-risk patients at discharge\n- **FHIR Integration** — modern API-based EHR connectivity replacing point-to-point spaghetti\n- **Value-Based Care Analytics** — CMS quality measure reporting, population health dashboards\n\nWe helped a Houston health system reduce 30-day readmissions by **34%, avoiding $3.1M in annual CMS penalties**. What's your biggest healthcare IT challenge right now?",
            'cta'        => true,
            'follow_ups' => ['How do you handle HIPAA compliance?','Can you integrate with Epic?','What does a healthcare AI project look like?'],
            'context'    => 'healthcare',
            'priority'   => 73,
        ],

        /* ── GOVERNMENT / DEFENSE / CMMC ────────────────────── */
        'govdef' => [
            'triggers'   => ['government'=>4,'federal'=>4,'defense'=>4,'dod'=>6,'department of defense'=>6,'cmmc'=>6,'fedramp'=>5,'dib'=>5,'defense contractor'=>6,'military'=>3,'federal contract'=>5,'prime contractor'=>4,'sub contractor'=>4,'nist 800'=>5,'fisma'=>5,'ato'=>5],
            'response'   => "Government and defense is a core market for us — we're based in the DC/Maryland corridor and have deep DoD supply chain experience.\n\nSpecifically:\n- **CMMC 2.0** — Level 1, 2, and 3 preparation, C3PAO assessment support, automated evidence collection\n- **FedRAMP** — cloud authorization support for SaaS providers seeking federal customers\n- **NIST 800-53 / 800-171** — gap assessments, SSP documentation, POA&M management\n- **Zero Trust** — NIST SP 800-207 implementation for agencies and contractors\n\nWe helped a Northern Virginia defense contractor achieve **CMMC Level 2 certification in 11 weeks**, protecting a $40M prime contract. What's your current compliance level and timeline?",
            'cta'        => true,
            'follow_ups' => ['What does CMMC Level 2 preparation involve?','How long does CMMC take?','Can you help with FedRAMP?'],
            'context'    => 'govdef',
            'priority'   => 74,
        ],

        /* ── PRICING ────────────────────────────────────────── */
        'pricing' => [
            'triggers'   => ['price'=>4,'pricing'=>5,'how much'=>5,'cost'=>4,'rates'=>4,'budget'=>3,'expensive'=>3,'affordable'=>3,'quote'=>5,'estimate'=>4,'hourly rate'=>5,'day rate'=>4,'fixed price'=>4,'time and material'=>4,'contract value'=>4,'what do you charge'=>5],
            'response'   => "We don't publish standard rates — every engagement is scoped to your specific challenge and team requirements.\n\nWhat we can share:\n- **Discovery Workshops** (2–5 days) typically run **$15,000–$40,000** and produce a roadmap you own regardless of next steps\n- **Fixed-scope projects** start at around **$75,000** for well-defined problems\n- **Ongoing retainers and managed services** are priced monthly based on scope\n- **Managed IT** is per-seat or per-device\n\nWe're not the cheapest option in the market — clients choose us because the outcomes we deliver justify the investment. The fastest way to get an accurate number is a 30-minute discovery call — it's free and there's no pressure.",
            'cta'        => true,
            'follow_ups' => ['Can I get a free discovery call?','Do you offer fixed-price contracts?','What\'s included in a discovery workshop?'],
            'context'    => 'pricing',
            'priority'   => 76,
        ],

        /* ── TIMELINE / HOW LONG ────────────────────────────── */
        'timeline' => [
            'triggers'   => ['how long'=>5,'timeline'=>5,'duration'=>4,'when can you start'=>4,'time to deliver'=>4,'weeks'=>3,'months'=>3,'turnaround'=>4,'delivery time'=>4,'time frame'=>4,'timeframe'=>4,'quickly'=>3,'fast'=>3,'urgent'=>3,'deadline'=>4],
            'response'   => "Timelines vary by scope, but here are typical ranges:\n\n- **Discovery Workshop** — 1–2 weeks to complete, roadmap in hand\n- **MVP / Proof of Concept** — 6–10 weeks\n- **Mid-size project** (single platform or migration) — 3–6 months\n- **Enterprise transformation program** — 12–24 months across phases\n\nWe work in agile sprints with bi-weekly demos, so you see progress continuously — not just at the end. For urgent needs (like CMMC certification deadlines), we've delivered in as little as 8–11 weeks. What's your situation?",
            'cta'        => false,
            'follow_ups' => ['Can you start immediately?','What are your typical phases?','How do you handle fixed deadlines?'],
            'context'    => 'timeline',
            'priority'   => 70,
        ],

        /* ── ENGAGEMENT PROCESS ─────────────────────────────── */
        'process' => [
            'triggers'   => ['how does it work'=>5,'engagement process'=>5,'how do we start'=>5,'getting started'=>5,'first steps'=>5,'discovery call'=>5,'next steps'=>4,'how do you work'=>4,'your process'=>4,'project phases'=>4,'methodology'=>4,'agile'=>3,'project management'=>3,'statement of work'=>4,'sow'=>4],
            'response'   => "Our engagement model is straightforward:\n\n**1. Discovery Call** (free, 30–45 min) — We listen to your challenge, share honest initial thoughts, and determine if there's a fit.\n\n**2. Discovery Workshop** (2–5 days) — Immersive sessions produce a prioritized roadmap, effort estimate, and business case. You own this deliverable regardless of next steps.\n\n**3. Proposal** — A clear statement of work with fixed price or T&M options, timeline, and team composition.\n\n**4. Delivery** — Agile sprints with bi-weekly demos, weekly status updates, and a shared risk register.\n\n**5. Post-Launch Partnership** — We remain your partner for optimization and expansion. 98% of clients renew.\n\nReady to start with a discovery call?",
            'cta'        => true,
            'follow_ups' => ['Schedule a discovery call','What happens in a discovery workshop?','Do I need to sign anything to start?'],
            'context'    => 'process',
            'priority'   => 74,
        ],

        /* ── CASE STUDIES / PROOF ───────────────────────────── */
        'case_studies' => [
            'triggers'   => ['case study'=>5,'case studies'=>5,'examples'=>4,'past work'=>4,'portfolio'=>4,'proof'=>3,'results'=>3,'success story'=>5,'client examples'=>5,'references'=>4,'testimonials'=>4,'who have you worked with'=>5,'previous clients'=>4,'track record'=>4,'have you done this before'=>4],
            'response'   => "Here are a few recent US client results:\n\n🏥 **Houston Health System** — 34% fewer 30-day readmissions, $3.1M in avoided CMS penalties (AI/ML)\n\n✈️ **Atlanta Logistics Corp** — $4.2M annual data center cost eliminated, 99.99% uptime (Cloud)\n\n🛡 **Northern Virginia Defense Contractor** — CMMC Level 2 in 11 weeks, $40M contract secured (Cybersecurity)\n\n🛍 **Chicago Specialty Retailer** — 29% revenue uplift, $8.4M overstock savings (Data Analytics)\n\n🏭 **San Antonio Manufacturer** — 43% OEE improvement, $4.8M annual savings (Digital Transformation)\n\nWe can share more specific case studies relevant to your industry. What sector are you in?",
            'cta'        => false,
            'follow_ups' => ['Show me healthcare examples','Show me manufacturing examples','Can I speak with a reference client?'],
            'context'    => 'case_studies',
            'priority'   => 72,
        ],

        /* ── DIFFERENTIATORS / WHY YOU ──────────────────────── */
        'why_us' => [
            'triggers'   => ['why choose'=>5,'why volkmann'=>5,'what makes you different'=>5,'differentiators'=>5,'why you'=>4,'competitive advantage'=>4,'vs other companies'=>4,'compared to others'=>4,'what sets you apart'=>5,'why not hire someone else'=>5,'unique value'=>4,'why should i use'=>4],
            'response'   => "Fair question — here's what makes us genuinely different:\n\n🇺🇸 **US-Based Delivery** — No offshore surprises. Delivery leads on every engagement are US-based and available in your time zone.\n\n📋 **Compliance Built In** — NIST, CMMC, HIPAA, SOX, FedRAMP expertise is part of every engagement, not an add-on.\n\n📏 **Outcomes, Not Hours** — We define success in business metrics upfront and measure everything. You'll always know what you're getting.\n\n🔒 **Fixed-Price Options** — We offer fixed-scope engagements where appropriate — no scope creep, no surprise invoices.\n\n🤝 **Long-Term Partnership** — 98% of our clients renew. We don't vanish after go-live.\n\nWould you like to discuss your specific situation?",
            'cta'        => true,
            'follow_ups' => ['How do you handle scope creep?','Can I see client references?','What does a fixed-price contract include?'],
            'context'    => 'why_us',
            'priority'   => 74,
        ],

        /* ── TEAM SIZE / STAFFING ───────────────────────────── */
        'team' => [
            'triggers'   => ['how many people'=>4,'team size'=>4,'how big is your team'=>5,'staff'=>3,'employees'=>3,'how many employees'=>5,'consultants'=>3,'specialists'=>3,'who would work on'=>4,'dedicated team'=>4,'team composition'=>4],
            'response'   => "We have over **150 technology specialists** across AI/ML engineering, cloud architecture, cybersecurity, data engineering, software development, and strategy consulting.\n\nFor each engagement we assemble a dedicated pod — typically a project manager, lead engineer or architect, and 2–4 specialists relevant to your use case. For larger programs, pods scale accordingly.\n\nAll team members are vetted senior professionals — we don't staff engagements with junior consultants as the primary delivery resource.",
            'cta'        => false,
            'follow_ups' => ['Can I meet the team before signing?','Do you have industry-specific specialists?'],
            'context'    => 'team',
            'priority'   => 68,
        ],

        /* ── DATA SECURITY / PRIVACY ────────────────────────── */
        'data_security' => [
            'triggers'   => ['data security'=>5,'data privacy'=>5,'our data is safe'=>4,'confidentiality'=>4,'nda'=>5,'non disclosure'=>5,'data handling'=>4,'will you see our data'=>4,'sensitive data'=>4,'protect our information'=>4,'data protection'=>4,'gdpr'=>4,'ccpa'=>4],
            'response'   => "Protecting client data is fundamental to how we operate.\n\n**What we do:**\n- Sign NDAs before any data is shared — standard on all engagements\n- Operate under SOC 2 Type II principles for data handling\n- Apply least-privilege access controls — only team members who need data see it\n- Use encrypted channels for all data transfer\n- CCPA and HIPAA data handling protocols available for regulated clients\n- Data is never used for any purpose beyond the stated engagement\n\nFor particularly sensitive environments (DoD, healthcare), we can operate within your security perimeter rather than accessing data externally. Any specific concern?",
            'cta'        => false,
            'follow_ups' => ['Do you sign NDAs?','Are you SOC 2 certified?','Can you work within our environment?'],
            'context'    => 'data_security',
            'priority'   => 74,
        ],

        /* ── FREE CONSULTATION ──────────────────────────────── */
        'consultation' => [
            'triggers'   => ['free consultation'=>6,'free discovery'=>6,'no obligation'=>5,'consultation'=>4,'discovery call'=>5,'intro call'=>4,'initial meeting'=>4,'free call'=>5,'can we talk'=>4,'set up a call'=>4,'book a meeting'=>4,'free assessment'=>5,'free review'=>5],
            'response'   => "Absolutely — our discovery calls are completely free with no obligation.\n\nA typical call runs **30–45 minutes**. We'll:\n- Listen to your challenge without a sales pitch\n- Share honest initial thoughts on approach and feasibility\n- Answer any questions about how we work\n- Tell you if we're genuinely the right fit (and suggest alternatives if we're not)\n\nYou can reach us at **{phone}** or fill out our contact form and we'll reach out to schedule.",
            'cta'        => true,
            'follow_ups' => ['What should I prepare for the call?','How quickly can you schedule?'],
            'context'    => 'consultation',
            'priority'   => 80,
        ],

        /* ── OFF-TOPIC / UNKNOWN ────────────────────────────── */
        'off_topic' => [
            'triggers'   => ['weather'=>5,'sports'=>5,'news'=>5,'politics'=>5,'recipe'=>5,'movie'=>5,'music'=>5,'joke'=>5,'personal'=>4,'not related'=>4,'random'=>4],
            'response'   => "That's a bit outside my expertise! I'm best at answering questions about Volkmann Express, our technology services, and how we help US enterprises.\n\nIs there anything I can help you with on the technology side?",
            'cta'        => false,
            'follow_ups' => ['What services do you offer?','How do I contact your team?'],
            'context'    => 'off_topic',
            'priority'   => 20,
        ],
    ];

    // Merge admin-defined custom Q&A entries
    $custom = get_option('ve_chatbot_custom_qa', []);
    foreach ($custom as $i => $qa) {
        if (empty($qa['keywords']) || empty($qa['answer'])) continue;
        $triggers = [];
        foreach (array_map('trim', explode(',', $qa['keywords'])) as $kw) {
            if ($kw) $triggers[$kw] = 4;
        }
        $intents['custom_' . $i] = [
            'triggers'   => $triggers,
            'response'   => $qa['answer'],
            'cta'        => !empty($qa['cta']),
            'follow_ups' => [],
            'context'    => 'custom',
            'priority'   => 78, // custom entries take priority over defaults
        ];
    }

    return $intents;
}

/* ═══════════════════════════════════════════════════════════════
   MATCHING ENGINE
   ═══════════════════════════════════════════════════════════════ */

function ve_chat_match( string $input, string $last_context = '' ): array {
    $intents = ve_chat_intents();
    $lower   = mb_strtolower( trim( $input ) );

    $scores = [];

    foreach ( $intents as $key => $intent ) {
        $score = 0;

        // Score by trigger matches
        foreach ( $intent['triggers'] as $phrase => $weight ) {
            if ( str_contains( $lower, $phrase ) ) {
                $score += $weight;
            }
        }

        // Context boost: if last intent was, say, 'cyber' and user says 'tell me more',
        // boost the same category
        if ( $last_context && $last_context === $key ) {
            $score += 3;
        }

        // "Tell me more" / "explain" → boost last context heavily
        if ( $last_context && preg_match('/tell me more|explain|elaborate|go on|more details|more info|continue|what else/i', $lower) ) {
            if ( $last_context === $key ) $score += 8;
        }

        // "yes" / "sure" / "ok" after a follow-up prompt
        if ( preg_match('/^(yes|sure|ok|okay|yep|yup|please|go ahead|sounds good|definitely)[\s.!]*$/i', $lower) ) {
            if ( $last_context === $key ) $score += 6;
        }

        if ( $score > 0 ) $scores[$key] = $score;
    }

    if ( empty( $scores ) ) {
        return ve_chat_fallback( $lower, $last_context );
    }

    // Sort by score descending, then by priority
    uasort( $scores, fn($a, $b) => $b <=> $a );
    $top_key   = array_key_first( $scores );
    $top_score = $scores[ $top_key ];

    // Threshold check
    if ( $top_score < 3 ) {
        return ve_chat_fallback( $lower, $last_context );
    }

    $intent = $intents[ $top_key ];

    // Substitute placeholders
    $phone   = get_theme_mod('ve_phone',   '+1 (301) 555-0100');
    $email   = get_theme_mod('ve_email',   'info@volkmannexpress.com');
    $address = get_theme_mod('ve_address', '12653 Hillmeade Station Drive, Bowie, MD 20720');
    $reply   = str_replace(
        ['{phone}','{email}','{address}','{contact}','{solutions}'],
        [$phone, $email, $address, home_url('/contact'), home_url('/solutions')],
        $intent['response']
    );

    return [
        'reply'       => $reply,
        'cta'         => $intent['cta'],
        'follow_ups'  => $intent['follow_ups'] ?? [],
        'context'     => $intent['context'],
        'matched'     => $top_key,
    ];
}

function ve_chat_fallback( string $lower, string $last_context ): array {
    $email   = get_theme_mod('ve_email',   'info@volkmannexpress.com');
    $contact = home_url('/contact');

    // Short / ambiguous input
    if ( strlen( $lower ) < 5 ) {
        return [
            'reply'      => "Could you give me a bit more detail? I want to make sure I give you the most useful answer.",
            'cta'        => false,
            'follow_ups' => ['What services do you offer?','How do I contact your team?','Tell me about your company'],
            'context'    => $last_context,
            'matched'    => 'fallback_short',
        ];
    }

    // Looks like a question we should know about but don't have matched
    if ( preg_match('/\b(price|cost|how much|quote|budget)\b/i', $lower) ) {
        return [
            'reply'      => "Pricing depends on the scope and complexity of your project — we don't have a one-size-fits-all rate card.\n\nThe quickest way to get a number is a free 30-minute discovery call. We'll understand your needs and give you a realistic range with no obligation.",
            'cta'        => true,
            'follow_ups' => ['Schedule a free call','What does a typical project cost?'],
            'context'    => 'pricing',
            'matched'    => 'fallback_pricing',
        ];
    }

    return [
        'reply'      => "I'm not sure I have a great answer for that specific question — I don't want to guess and give you wrong information.\n\nFor anything I can't confidently answer, I'd recommend reaching out to our team directly at **{$email}** or using the contact form. They'll give you a proper response within one business day.",
        'cta'        => true,
        'follow_ups' => ['What services do you offer?','How do I contact your team?','Tell me about your company'],
        'context'    => $last_context,
        'matched'    => 'fallback_generic',
    ];
}

/* ═══════════════════════════════════════════════════════════════
   AJAX HANDLER
   ═══════════════════════════════════════════════════════════════ */

function ve_chatbot_respond() {
    check_ajax_referer( 've_chat_nonce', 'nonce' );

    $message      = sanitize_textarea_field( $_POST['message']      ?? '' );
    $last_context = sanitize_text_field(     $_POST['last_context'] ?? '' );

    if ( ! $message ) {
        wp_send_json_error( ['message' => 'Empty message.'] );
    }

    // Rate limiting: max 60 requests per IP per hour (stored in transients)
    $ip_key = 've_chat_rate_' . md5( $_SERVER['REMOTE_ADDR'] ?? 'unknown' );
    $count  = (int) get_transient( $ip_key );
    if ( $count >= 60 ) {
        wp_send_json_success([
            'reply'      => "You've sent a lot of messages! Please reach out to our team directly for more detailed help.",
            'cta'        => true,
            'follow_ups' => [],
            'context'    => $last_context,
        ]);
    }
    set_transient( $ip_key, $count + 1, HOUR_IN_SECONDS );

    $result = ve_chat_match( $message, $last_context );

    // Log the conversation turn (privacy-safe — no IP stored)
    ve_chat_log( $message, $result['reply'], $result['matched'] ?? '' );

    wp_send_json_success([
        'reply'      => $result['reply'],
        'cta'        => $result['cta'],
        'follow_ups' => $result['follow_ups'],
        'context'    => $result['context'],
    ]);
}
add_action( 'wp_ajax_ve_chatbot',        've_chatbot_respond' );
add_action( 'wp_ajax_nopriv_ve_chatbot', 've_chatbot_respond' );

/* ═══════════════════════════════════════════════════════════════
   CONVERSATION LOGGING (admin-viewable, no PII stored)
   ═══════════════════════════════════════════════════════════════ */

function ve_chat_log( string $message, string $reply, string $intent ): void {
    $log   = get_option( 've_chat_log', [] );
    $log[] = [
        'time'    => current_time( 'mysql' ),
        'message' => mb_substr( $message, 0, 200 ),
        'intent'  => $intent,
        'reply'   => mb_substr( $reply, 0, 100 ) . '…',
    ];
    // Keep last 500 entries
    if ( count( $log ) > 500 ) {
        $log = array_slice( $log, -500 );
    }
    update_option( 've_chat_log', $log );
}

/* ═══════════════════════════════════════════════════════════════
   ADMIN PAGES
   ═══════════════════════════════════════════════════════════════ */

function ve_chatbot_admin_menu() {
    add_submenu_page( 've-leads', 'Chatbot Q&A',  'Chatbot Q&A',  'manage_options', 've-chatbot-qa',  've_chatbot_qa_page' );
    add_submenu_page( 've-leads', 'Chat Log',      'Chat Log',      'manage_options', 've-chat-log',    've_chat_log_page' );
}
add_action( 'admin_menu', 've_chatbot_admin_menu' );

function ve_chatbot_qa_page(): void {
    // Save
    if ( isset( $_POST['ve_qa_save'] ) && check_admin_referer( 've_qa_nonce' ) ) {
        $entries = [];
        $kws     = $_POST['ve_kw']     ?? [];
        $ans     = $_POST['ve_ans']    ?? [];
        $ctas    = $_POST['ve_cta']    ?? [];
        foreach ( $kws as $i => $kw ) {
            $kw  = sanitize_text_field( $kw );
            $ans_i = sanitize_textarea_field( $ans[$i] ?? '' );
            if ( $kw && $ans_i ) {
                $entries[] = ['keywords' => $kw, 'answer' => $ans_i, 'cta' => !empty($ctas[$i])];
            }
        }
        update_option( 've_chatbot_custom_qa', $entries );
        echo '<div class="notice notice-success"><p>Custom Q&A saved.</p></div>';
    }

    $entries = get_option( 've_chatbot_custom_qa', [] );
    ?>
    <div class="wrap">
        <h1>Volkmann Express — Chatbot Custom Q&amp;A</h1>
        <p>Add custom question-and-answer pairs. The chatbot matches user messages by keyword — separate multiple keywords/phrases with commas.</p>
        <form method="post" id="ve-qa-form">
            <?php wp_nonce_field( 've_qa_nonce' ); ?>
            <table class="widefat striped" id="ve-qa-table">
                <thead><tr><th>Keywords / Phrases (comma-separated)</th><th>Answer</th><th>Show CTA?</th><th></th></tr></thead>
                <tbody>
                <?php foreach ( $entries as $i => $e ) : ?>
                <tr>
                    <td><input type="text" name="ve_kw[<?=$i?>]" value="<?=esc_attr($e['keywords'])?>" class="regular-text" placeholder="pricing, how much, quote"></td>
                    <td><textarea name="ve_ans[<?=$i?>]" rows="3" class="large-text"><?=esc_textarea($e['answer'])?></textarea></td>
                    <td style="text-align:center;"><input type="checkbox" name="ve_cta[<?=$i?>]" value="1" <?=checked(!empty($e['cta']),true,false)?>></td>
                    <td><button type="button" class="button ve-qa-remove" onclick="this.closest('tr').remove()">Remove</button></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p>
                <button type="button" class="button" id="ve-qa-add">+ Add Row</button>
                <?php submit_button( 'Save All', 'primary', 've_qa_save', false, ['style'=>'margin-left:1rem;'] ); ?>
            </p>
        </form>
        <hr>
        <h2>Built-In Intents (read-only reference)</h2>
        <p>The chatbot has <strong><?= count(ve_chat_intents()) ?></strong> built-in intents including all 8 services, 12 industries, pricing, process, case studies, compliance topics, and more. Use the table above to add or override specific answers.</p>
    </div>
    <script>
    let rowIdx = <?= count($entries) ?>;
    document.getElementById('ve-qa-add').addEventListener('click', () => {
        const tbody = document.querySelector('#ve-qa-table tbody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="ve_kw[${rowIdx}]" class="regular-text" placeholder="keyword one, keyword two"></td>
            <td><textarea name="ve_ans[${rowIdx}]" rows="3" class="large-text"></textarea></td>
            <td style="text-align:center;"><input type="checkbox" name="ve_cta[${rowIdx}]" value="1"></td>
            <td><button type="button" class="button ve-qa-remove" onclick="this.closest('tr').remove()">Remove</button></td>`;
        tbody.appendChild(tr);
        rowIdx++;
    });
    </script>
    <?php
}

function ve_chat_log_page(): void {
    if ( isset($_POST['ve_clear_log']) && check_admin_referer('ve_clear_log_nonce') ) {
        delete_option('ve_chat_log');
        echo '<div class="notice notice-success"><p>Log cleared.</p></div>';
    }
    $log = array_reverse( get_option('ve_chat_log', []) );
    echo '<div class="wrap"><h1>Volkmann Express — Chat Log</h1>';
    echo '<form method="post" style="margin-bottom:1rem;">' . wp_nonce_field('ve_clear_log_nonce','_wpnonce',true,false);
    echo '<button name="ve_clear_log" class="button" onclick="return confirm(\'Clear all log entries?\')">Clear Log</button></form>';
    if ( empty($log) ) { echo '<p>No chat messages logged yet.</p></div>'; return; }
    echo '<p>' . count($log) . ' entries (last 500 kept, no IP addresses stored).</p>';
    echo '<table class="widefat striped"><thead><tr><th>Time</th><th>User Message</th><th>Intent Matched</th><th>Bot Reply (truncated)</th></tr></thead><tbody>';
    foreach ( $log as $l ) {
        printf(
            '<tr><td style="white-space:nowrap;">%s</td><td>%s</td><td><code>%s</code></td><td>%s</td></tr>',
            esc_html($l['time'] ?? ''), esc_html($l['message'] ?? ''),
            esc_html($l['intent'] ?? 'unknown'), esc_html($l['reply'] ?? '')
        );
    }
    echo '</tbody></table></div>';
}

/* ═══════════════════════════════════════════════════════════════
   ENQUEUE NONCE & CONFIG
   ═══════════════════════════════════════════════════════════════ */

function ve_chatbot_localize(): void {
    wp_localize_script( 've-main', 'VE_CHAT', [
        'nonce'      => wp_create_nonce( 've_chat_nonce' ),
        'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
        'contactUrl' => home_url( '/contact' ),
        'email'      => get_theme_mod( 've_email', 'info@volkmannexpress.com' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 've_chatbot_localize', 20 );
