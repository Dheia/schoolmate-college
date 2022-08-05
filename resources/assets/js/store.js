import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const state = { 

	quiz: {
		surveyRef: null,
		pages: [
		{
			name: "page1",
		   	elements: [
		    ]
		}]

	},

	smartCard: {
		cardType: "front",
		frontCard: [
			{
				title: 'backgroundImage',
				ref: 'backgroundImage',
				type: 'image',
				value: null,
			}
		],
		backCard: [
			{
				title: 'backgroundImage',
				ref: 'backgroundImage',
				type: 'image',
				value: null,
			}
		],

		frontCardRef: null,
		backCardRef: null,
	}
};


const getters = { 
	cardType: state => state.cardType,
};

const store = new Vuex.Store({ state, getters });

export default store;