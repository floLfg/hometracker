<template>
	<div v-bind:class="{active: $route.name === 'home'}" class="menu">
		<router-link :to="{name: 'home'}" :class="{active: $route.name === 'home'}">
			<img height="22" src="/img/home.png">
			Home
		</router-link>

        <router-link :to="{name: 'spendings'}" :class="{active: $route.name === 'spendings'}">
        	<img height="20" src="/img/spendings.png">
        	Dépenses
    	</router-link>

        <router-link :to="{name: 'synthesis'}" :class="{active: $route.name === 'synthesis'}">
        	<img height="22" src="/img/charts.png">
        	Synthèse
    	</router-link>

        <router-link :to="{name: 'savings'}" :class="{active: $route.name === 'savings'}">
            <img height="22" src="/img/savings.png">
            Économies
        </router-link>

        <router-link :to="{name: 'users'}" :class="{active: $route.name === 'users'}">
        	<img height="22" src="/img/members.png">
        	Membres
    	</router-link>

        <a href="/logout">
        	<img height="22" src="/img/logout.png">
        	Déconnexion
    	</a>
    </div>
</template>