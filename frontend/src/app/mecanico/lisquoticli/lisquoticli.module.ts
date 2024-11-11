import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { LisquoticliPageRoutingModule } from './lisquoticli-routing.module';

import { LisquoticliPage } from './lisquoticli.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    LisquoticliPageRoutingModule
  ],
  declarations: [LisquoticliPage]
})
export class LisquoticliPageModule {}
