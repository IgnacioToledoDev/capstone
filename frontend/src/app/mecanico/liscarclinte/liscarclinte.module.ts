import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { LiscarclintePageRoutingModule } from './liscarclinte-routing.module';

import { LiscarclintePage } from './liscarclinte.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    LiscarclintePageRoutingModule
  ],
  declarations: [LiscarclintePage]
})
export class LiscarclintePageModule {}
