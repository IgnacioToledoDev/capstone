import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { LisReservasPage } from './lis-reservas.page';

const routes: Routes = [
  {
    path: '',
    component: LisReservasPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class LisReservasPageRoutingModule {}
