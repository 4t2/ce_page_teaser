
<div class="<?php echo $this->class; ?> ce_text block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><a href="<?php echo $this->href; ?>"><?php echo $this->headline; ?></a></<?php echo $this->hl; ?>>
<?php endif; ?>

<?php if (!$this->addBefore): ?>

<?php echo $this->text; ?>
<?php endif; ?>
<?php if ($this->addImage): ?>

<div class="image_container<?php echo $this->floatClass; ?>"<?php if ($this->margin || $this->float): ?> style="<?php echo trim($this->margin . $this->float); ?>"<?php endif; ?>>
<?php if ($this->href): ?>
<a href="<?php echo $this->href; ?>"<?php echo $this->attributes; ?> title="<?php echo $this->alt; ?>">
<?php endif; ?>
<img src="<?php echo $this->src; ?>"<?php echo $this->imgSize; ?> alt="<?php echo $this->alt; ?>">
<?php if ($this->href): ?>
</a>
<?php endif; ?>
<?php if ($this->caption): ?>
<div class="caption"><?php echo $this->caption; ?></div>
<?php endif; ?>
</div>
<?php endif; ?>
<?php if ($this->addBefore): ?>

<?php echo $this->text; ?>
<?php endif; ?>

<?php if ($this->showMore): ?>
<a href="<?php echo $this->href; ?>" title="<?php echo $this->readMore; ?>" class="more"><?php echo $this->more; ?> <span class="invisible"><?php echo $this->headline; ?></span></a>
<?php endif; ?>

</div>