<div class="balance">
    <p class="desc">
        <b class="app_invoice_link transition">
            <a href="<?= url("app/demonstrativo-condominio/{$invoice->id}"); ?>", title="Demonstrativo <?= $invoice->id; ?>">
                <?= date_fmt($invoice->month_ref, "m/Y"); ?>
            </a></b>        
    </p>
    <p class="price">
        <?= ($invoice->consumption); ?> m<sup>3</sup>
    </p>
</div>