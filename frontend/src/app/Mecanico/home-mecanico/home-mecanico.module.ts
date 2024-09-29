import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { HomeMecanicoPageRoutingModule } from './home-mecanico-routing.module';

import { HomeMecanicoPage } from './home-mecanico.page';
import { ComponentsModule } from 'src/app/components/components.module';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    HomeMecanicoPageRoutingModule,
    ComponentsModule
  ],
  declarations: [HomeMecanicoPage,]
})
export class HomeMecanicoPageModule {}
