mo_betta_meta
=============

ExpressionEngine module to render schema tags for the following social media platforms:

1. Schema.org
  1. name
  2. description
  3. image
2. Twitter
  1. card
  2. title
  3. description
  4. image
3. OpenGraph
  1. type
  2. title
  3. description
  4. image
  5. url

#### Dependencies

* EE2
* NSM Better Meta

### Usage

1. Download and copy to `system/admin/expressionengine/third_party`
2. Install from `Add-Ons >> Modules`
3. Configure from `Add-ons >> Modules >> Mo Betta Meta Tags`
4. Add tag to template(s)

### Template example

    <head>
    
      {exp:channel:entries}
      
        {exp:nsm_beter_meta:template entry_id="{entry_id}"}
    
        {exp:mo_betta_meta:tags entry_id="{entry_id}"}
      
      {/exp:channel:entries}

    </head>
