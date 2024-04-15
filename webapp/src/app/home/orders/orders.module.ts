import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { OrdersComponent } from './orders.component';
import {OrderRoutingModule} from "./order-routing.module";
import {NgxPaginationModule} from "ngx-pagination";
import {FormsModule} from "@angular/forms";

@NgModule({
  declarations: [
    OrdersComponent
  ],
  imports: [
    CommonModule,
    OrderRoutingModule,
    NgxPaginationModule,
    FormsModule
  ]
})
export class OrdersModule { }
