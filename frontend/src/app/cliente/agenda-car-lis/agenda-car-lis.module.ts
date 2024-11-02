import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AgendaCarLisPageRoutingModule } from './agenda-car-lis-routing.module';

import { AgendaCarLisPage } from './agenda-car-lis.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    AgendaCarLisPageRoutingModule
  ],
  declarations: [AgendaCarLisPage]
})
export class AgendaCarLisPageModule {}
