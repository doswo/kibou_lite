<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * functions.php
 *
 * Author     : metheno
 * Date       : 2017/02/11
 * Version    :
 * Description:
 */

require_once("lib/PluginCheck.php");
require_once("lib/PostRenderer.php");
require_once("lib/UACheck.php");

function themeConfig($form) {

  $enableMathJax = new Typecho_Widget_Helper_Form_Element_Radio('enableMathJax',
    array('1' => _t('开启'),
    '0' => _t('关闭')),
    '0', _t('MathJax 支持'), _t('默认为关闭。<br/>单行：<code>$...$</code>；<br/>多行：<code>$$...$$</code>。'));
  $form->addInput($enableMathJax);

  $donateQRLink = new Typecho_Widget_Helper_Form_Element_Text('donateQRLink', NULL, NULL,
  _t('赞赏二维码'), _t('在文章页内插入一个用于打赏的二维码。'));
  $form->addInput($donateQRLink);

  $beianNumber = new Typecho_Widget_Helper_Form_Element_Text('beianNumber', NULL, NULL,
  _t('备案号'), _t('如果已经备案，请填写备案号。'));
  $form->addInput($beianNumber);

  $additionalJS = new Typecho_Widget_Helper_Form_Element_Textarea('additionalJS', NULL, NULL,
  _t('JS 代码'), _t('填写其他 JS 代码。不需要加 <code>script</code> 标签。'));
  $form->addInput($additionalJS);

}

function prev_post($archive)
{
  $db = Typecho_Db::get();
  $content = $db->fetchRow($db->select()
                              ->from('table.contents')
                              ->where('table.contents.created < ?', $archive->created)
                              ->where('table.contents.status = ?', 'publish')
                              ->where('table.contents.type = ?', $archive->type)
                              ->where('table.contents.password IS NULL')
                              ->order('table.contents.created', Typecho_Db::SORT_DESC)
                              ->limit(1));
  if ($content)
  {
    $content = Typecho_Widget::widget('Widget_Abstract_Contents')->filter($content);
    echo '<a class="prev" href="' . $content['permalink'] . '" rel="prev"><span>上一篇</span><br/>' . $content['title'] . '</a>';
  } else {
    echo "<a class=\"prev\"><span>\xf0\x9F\x98\xb6</span><br/>没有更多了</a>";
  }
}

function next_post($archive)
{
  $db = Typecho_Db::get();
  $content = $db->fetchRow($db->select()
                              ->from('table.contents')
                              ->where('table.contents.created > ? AND table.contents.created < ?', $archive->created, Helper::options()->gmtTime)
                              ->where('table.contents.status = ?', 'publish')
                              ->where('table.contents.type = ?', $archive->type)
                              ->where('table.contents.password IS NULL')
                              ->order('table.contents.created', Typecho_Db::SORT_ASC)
                              ->limit(1));
                              
  if ($content)
  {
    $content = Typecho_Widget::widget('Widget_Abstract_Contents')->filter($content);
    echo '<a class="next" href="' . $content['permalink'] . '" rel="next"><span>下一篇</span><br/>' . $content['title'] . '</a>';
  } else {
    echo "<a class=\"next\"><span>\xf0\x9F\x98\xb6</span><br/>没有更多了</a>";
  }
}
