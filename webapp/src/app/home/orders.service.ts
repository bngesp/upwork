import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {IOrder, IOrderResponse, SearchOrderItem} from "../business/model/order.model";
import {Observable} from "rxjs";

const URL_BACKEND = 'http://localhost:8000/api/orders'

@Injectable({
  providedIn: 'root'
})
export class OrdersService {

  constructor(private http: HttpClient) { }

  getAllOrder(): Observable<IOrder[]>{
    return this.http.get<IOrder[]>(URL_BACKEND)
  }

  searchOrder(search : SearchOrderItem): Observable<IOrder[]> {
    return this.http.get<IOrder[]>(URL_BACKEND + '?search=' + search.search + '&page=' + search.page)
  }

  cancelOrder(id: number): Observable<IOrder[]> {
    return this.http.post<IOrder[]>(URL_BACKEND + '/cancel/'+id, id)
  }
}
