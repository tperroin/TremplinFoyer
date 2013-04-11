<?php

/* SonataNewsBundle:Mail:comment_notification.txt.twig */
class __TwigTemplate_a8dc41594c6310b49d88f8d0c4d9a598 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        echo $this->env->getExtension('translator')->trans("mail_title_comment_notification", array("%post_title%" => $this->getAttribute($this->getContext($context, "post"), "title"), "%blog_title%" => $this->getAttribute($this->getContext($context, "blog"), "title"), "%comment_message%" => $this->getAttribute($this->getContext($context, "comment"), "message"), "%comment_email%" => $this->getAttribute($this->getContext($context, "comment"), "email"), "%comment_url%" => $this->getAttribute($this->getContext($context, "comment"), "url"), "%comment_name%" => $this->getAttribute($this->getContext($context, "comment"), "name"), "%post_url%" => $this->env->getExtension('routing')->getUrl("sonata_news_view", array("permalink" => $this->getAttribute($this->getAttribute($this->getContext($context, "blog"), "permalinkGenerator"), "generate", array(0 => $this->getContext($context, "post")), "method")), true), "%comment_invalid_link%" => $this->env->getExtension('routing')->getUrl("sonata_news_comment_moderation", array("commentId" => $this->getAttribute($this->getContext($context, "comment"), "id"), "hash" => $this->getContext($context, "hash"), "status" => 0), true), "%comment_valid_link%" => $this->env->getExtension('routing')->getUrl("sonata_news_comment_moderation", array("commentId" => $this->getAttribute($this->getContext($context, "comment"), "id"), "hash" => $this->getContext($context, "hash"), "status" => 1), true)), "SonataNewsBundle");
        // line 12
        echo "
";
    }

    public function getTemplateName()
    {
        return "SonataNewsBundle:Mail:comment_notification.txt.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  21 => 12,  19 => 2,);
    }
}
