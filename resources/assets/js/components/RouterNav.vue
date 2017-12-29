<template>
	<div v-bind:class="{active: $route.name === 'home'}" class="menu">
		<router-link :to="{name: 'home'}" :class="{active: $route.name === 'home'}">Home</router-link>

        <router-link :to="{name: 'spendings'}" :class="{active: $route.name === 'spendings'}">Dépenses</router-link>

        <router-link :to="{name: 'synthesis'}" :class="{active: $route.name === 'synthesis'}">Synthèse</router-link>

        <router-link :to="{name: 'users'}" :class="{active: $route.name === 'users'}">Membres</router-link>

        <a href="/logout">Déconnexion</a>
    </div>
</template>