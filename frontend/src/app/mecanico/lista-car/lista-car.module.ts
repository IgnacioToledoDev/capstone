import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ListaCarPageRoutingModule } from './lista-car-routing.module';

import { ListaCarPage } from './lista-car.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ListaCarPageRoutingModule
  ],
  declarations: [ListaCarPage]
})
export class ListaCarPageModule {}
