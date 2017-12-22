{config_load file="$skin_config"}{if $contact.subject}{$contact.subject}{else}{ $config.Company.company_name }: {$lng.eml_contact_us_subj}{/if}
