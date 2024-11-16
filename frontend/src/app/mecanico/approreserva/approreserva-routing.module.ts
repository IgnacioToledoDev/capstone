import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ApproreservaPage } from './approreserva.page';

const routes: Routes = [
  {
    path: '',
    component: ApproreservaPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ApproreservaPageRoutingModule {}
