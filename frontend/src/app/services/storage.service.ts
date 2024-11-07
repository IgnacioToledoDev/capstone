import { Injectable } from '@angular/core';
import { Storage } from '@ionic/storage-angular';

@Injectable({
  providedIn: 'root'
})
export class StorageService {
  private _storage: Storage | null = null;

  constructor(private storage: Storage) {
    this.init();
  }

  async init() {
    const storage = await this.storage.create();
    this._storage = storage;
  }


  public set(key: string, value: any) {
    this._storage?.set(key, value);
  }

  public remove(key: string) {
    this._storage?.remove(key);
  }

  public async get(key: string) {
    const value = await this._storage?.get(key);
    console.log('obteniendo el valor');
    return value;
  }

  public async keys() {
    const keys = await this._storage?.keys();
    return keys;
  }
}
