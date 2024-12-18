import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { BienvenidosPageRoutingModule } from './bienvenidos-routing.module';

import { BienvenidosPage } from './bienvenidos.page';
import { MenuComponent } from '../components/menu/menu.component';


@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    BienvenidosPageRoutingModule
  ],
  declarations: [BienvenidosPage]
})
export class BienvenidosPageModule {}
