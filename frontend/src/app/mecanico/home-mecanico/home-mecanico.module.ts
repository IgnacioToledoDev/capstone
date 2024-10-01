import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { HomeMecanicoPageRoutingModule } from './home-mecanico-routing.module';

import { HomeMecanicoPage } from './home-mecanico.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    HomeMecanicoPageRoutingModule
  ],
  declarations: [HomeMecanicoPage]
})
export class HomeMecanicoPageModule {}
