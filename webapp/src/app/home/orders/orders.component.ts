import { Component, OnInit } from '@angular/core';
import {IOrder, SearchOrderItem} from "../../business/model/order.model";
import {OrdersService} from "../orders.service";

@Component({
  selector: 'app-orders',
  templateUrl: './orders.component.html',
  styleUrls: ['./orders.component.css']
})
export class OrdersComponent implements OnInit {
  orders: IOrder[] = [];
  page: number = 1;
  search: string = '';
  message: string = '';
  searchOrderItem  : SearchOrderItem = {
    search: '',
    page: 1
  }

  constructor(private orderService: OrdersService) { }

  ngOnInit(): void {
    this.orderService.getAllOrder().subscribe({
      next: (data) => {
        this.orders = data;
      },
      error: err => { console.error('Error: ', err)}
    })
  }

  pageChanged($event: number) {
    this.page = $event;
    this.handleSearchAndPageChange();
  }

  searchTerm() {
    this.handleSearchAndPageChange();
  }

  handleSearchAndPageChange() {
    this.searchOrderItem = {
      search: this.search,
      page: this.page
    }
    this.orderService.searchOrder(this.searchOrderItem).subscribe({
      next:(data)=> {this.orders = data},
      error: err => { console.error('Error: ', err)}
    });
  }

  cancel(id: number) {
    this.orderService.cancelOrder(id).subscribe({
      next:(data)=> {this.orders = data
      this.message = 'Order has been cancelled'},
      error: err => { console.error('Error: ', err)}
    });
  }
}
