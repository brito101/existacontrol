<div class="balance">
    <p class="desc">
        <b class="app_invoice_link transition">
            <a href="<?= url("app/demonstrativo/{$invoice->id}"); ?>", title="Demonstrativo <?= $invoice->id; ?>">
                 <?= date('m/Y', strtotime($invoice->due_at)); ?>
            </a></b>        
    </p>
    <p class="price">
        <?= ($invoice->consumption); ?> m<sup>3</sup>
    </p>
</div>