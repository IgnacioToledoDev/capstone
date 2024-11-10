import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { QrinfoPage } from './qrinfo.page';

const routes: Routes = [
  {
    path: '',
    component: QrinfoPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class QrinfoPageRoutingModule {}
