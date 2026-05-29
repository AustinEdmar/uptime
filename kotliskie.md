1 - viewmodel 
package org.austin.buzza

import com.rickclephas.kmp.nativecoroutines.NativeCoroutinesState
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch


data class Articles(
    val id: Int,
    val article: String,
    val price: Double
    )



class SharedViewModel {


     private val viewModelScope = CoroutineScope(Dispatchers.Default)
    private val _articles = MutableStateFlow<List<Articles>>(emptyList())
    @NativeCoroutinesState
    var articles: StateFlow<List<Articles>> = _articles

    private val _total = MutableStateFlow(0.0)
    val total: StateFlow<Double> = _total


    private var id = 1
    fun addArticle(article: String, price: Double){
        viewModelScope.launch {
           val newArticle = Articles(id++, article, price)
            _articles.value += newArticle
            recalculateTotal()
        }
    }

    fun removeArticle(id: Int){
        viewModelScope.launch {
            _articles.value = _articles.value.filter { it.id != id }
            recalculateTotal()
        }



    }

    private fun recalculateTotal(){
        viewModelScope.launch {
            _total.value = _articles.value.sumOf { it.price }
        }
    }




    init {
        loadArticles()
    }
    private fun loadArticles(){
        viewModelScope.launch {
            _articles.value = listOf(
                Articles(1, "Carne", 1200.00),
                Articles(2, "Peixe", 3000.00),
                Articles(3, "Arroz", 1000.00)
            )
        }
    }
}