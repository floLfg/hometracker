<template>
    <div class="blue-box">
        <ul class="nav nav-pills">
            <li class="active">
                <button @click="toggleCharts('total')" class="toggle-charts btn btn-default active total">Synthèse</button>
            </li>
            <li>
                <button @click="toggleCharts('dividing')" class="toggle-charts btn btn-default dividing">Répartition</button>
            </li>
        </ul>
        <br>
        <div class="tab-content">
            <div data-title="total" class="tab-pane fade in active">
                <spendings-total ref="spendingsTotalChart"></spendings-total>
            </div>
            <div data-title="dividing" class="tab-pane fade">
                <spendings-dividing ref="spendingsDividingChart"></spendings-dividing>
            </div>  
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            toggleCharts(title) {
                $('.tab-pane[data-title='+title+']').addClass('in').addClass('active');
                $('button.toggle-charts.'+title).addClass('active');

                $('.tab-pane:not([data-title='+title+'])').removeClass('in').removeClass('active');
                $('button.toggle-charts:not(.'+title+')').removeClass('active');
            }
        }
    }
</script>